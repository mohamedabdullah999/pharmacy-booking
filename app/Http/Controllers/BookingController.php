<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Booking;
use App\Models\ItemPricingRule;
use App\Jobs\GenerateBookingReceipt;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function store(Request $request, $itemId)
    {
        $item = Item::findOrFail($itemId);
        
        $rules = [
            'pricing_rule_id' => 'required|exists:item_pricing_rules,id',
            'customer_name' => 'required|string|max:255',
            'customer_national_id' => 'required|string|size:14',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'required|email|max:255',
        ];

        $isHourly = false;
        if ($request->has('pricing_rule_id')) {
            $pricingRule = ItemPricingRule::where('id', $request->pricing_rule_id)
                                          ->where('item_id', $item->id)
                                          ->first();
            if ($pricingRule) {
                $unit = trim(strtolower($pricingRule->unit_type));
                $isHourly = in_array($unit, ['hr', 'hour', 'ساعة', 'ساعات']);
            }
        }

        if ($item->type === 'sale') {
            $rules['requested_amount'] = 'required|integer|min:1'; 
        } else {
            $rules['requested_amount'] = 'required|numeric|min:0.5';
            $rules['booking_date'] = 'required|date|after_or_equal:today';
            $rules['start_time'] = 'required|date_format:H:i';
            
            if (!$isHourly) {
                $rules['end_time'] = 'required|date_format:H:i|after:start_time'; 
            }
        }

        $validated = $request->validate($rules);

        DB::beginTransaction();

        try {
            $lockedItem = Item::where('id', $itemId)->lockForUpdate()->firstOrFail();

            $pricingRule = ItemPricingRule::where('id', $validated['pricing_rule_id'])
                                          ->where('item_id', $lockedItem->id)
                                          ->firstOrFail();

            if ($pricingRule->min_duration && $validated['requested_amount'] < $pricingRule->min_duration) {
                DB::rollBack();
                return back()->withErrors(['amount' => 'الكمية أو المدة المطلوبة أقل من الحد الأدنى.'])->withInput();
            }

            $startTimeFormatted = null;
            $endTimeFormatted = null;
            $bookingDate = null;

            if ($lockedItem->type === 'rental') {
                $bookingDate = $validated['booking_date'];
                $startDateTime = Carbon::parse($bookingDate . ' ' . $validated['start_time']);
                
                if ($isHourly) {
                    $requestedMinutes = (float)$validated['requested_amount'] * 60;
                    $endDateTime = $startDateTime->copy()->addMinutes($requestedMinutes);
                } else {
                    $endDateTime = Carbon::parse($bookingDate . ' ' . $validated['end_time']);
                }
                
                if ($startDateTime->isFriday()) {
                    DB::rollBack();
                    return back()->withErrors(['date' => 'يوم الجمعة عطلة رسمية. يرجى اختيار يوم آخر.'])->withInput();
                }

                $businessStart = Carbon::parse($bookingDate . ' 09:00:00');
                $businessEnd = Carbon::parse($bookingDate . ' 17:00:00'); 

                if ($startDateTime->lt($businessStart)) {
                    DB::rollBack();
                    return back()->withErrors(['time' => 'مواعيد العمل تبدأ من 9:00 صباحاً.'])->withInput();
                }
                if ($endDateTime->gt($businessEnd)) {
                    DB::rollBack();
                    return back()->withErrors(['time' => 'لا يمكن أن يتخطى وقت الانتهاء موعد إغلاق الكلية (5:00 مساءً).'])->withInput();
                }

                $overlapping = Booking::where('item_id', $lockedItem->id)
                    ->where('booking_date', $bookingDate)
                    ->whereIn('status', ['pending', 'paid'])
                    ->where(function ($query) use ($startDateTime, $endDateTime) {
                        $query->where('start_time', '<', $endDateTime->format('H:i:s'))
                              ->where('end_time', '>', $startDateTime->format('H:i:s'));
                    })->exists();

                if ($overlapping) {
                    DB::rollBack();
                    return back()->withErrors(['time' => 'هذا الوقت يتعارض مع حجز مسبق، يرجى مراجعة جدول الأوقات المحجوزة واختيار وقت آخر.'])->withInput();
                }
                
                $startTimeFormatted = $startDateTime->format('H:i:s');
                $endTimeFormatted = $endDateTime->format('H:i:s');

            } elseif ($lockedItem->type === 'sale') {
                if ($validated['requested_amount'] > $lockedItem->stock_quantity) {
                    DB::rollBack();
                    return back()->withErrors(['amount' => 'الكمية المطلوبة غير متوفرة في المخزون حالياً.'])->withInput();
                }
                $lockedItem->stock_quantity -= $validated['requested_amount'];
                $lockedItem->save();
            }

            $totalPrice = $pricingRule->price * $validated['requested_amount'];
            $referenceNumber = 'ZU-' . strtoupper(Str::random(8));

            $booking = Booking::create([
                'reference_number' => $referenceNumber,
                'item_id' => $lockedItem->id,
                'pricing_rule_id' => $pricingRule->id,
                'customer_name' => $validated['customer_name'],
                'customer_national_id' => $validated['customer_national_id'],
                'customer_phone' => $validated['customer_phone'],
                'customer_email' => $validated['customer_email'],
                'requested_amount' => $validated['requested_amount'],
                'total_price' => $totalPrice,
                'booking_date' => $bookingDate,
                'start_time' => $startTimeFormatted,
                'end_time' => $endTimeFormatted,
                'status' => 'pending',
                'expires_at' => now()->addHours(24), 
            ]);

            DB::commit();
            
            GenerateBookingReceipt::dispatch($booking);

            return redirect()->route('booking.success', $booking->reference_number);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'حدث خطأ أثناء المعالجة، حاول مرة أخرى.'])->withInput();
        }
    }

    public function success($reference)
    {
        $booking = Booking::with('item', 'pricingRule')->where('reference_number', $reference)->firstOrFail();
        return view('catalog.success', compact('booking'));
    }

    public function downloadReceipt($reference)
    {
        $booking = Booking::where('reference_number', $reference)->firstOrFail();
        
        $fileName = 'receipt_' . $booking->reference_number . '.pdf';
        $filePath = 'receipts/' . $fileName;

        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($filePath)) {
            return response()->download(storage_path('app/public/' . $filePath));
        }

        return back()->with('warning', 'جاري تجهيز الإيصال الإلكتروني... يرجى تحديث الصفحة والضغط على الزر مرة أخرى بعد ثوانٍ قليلة.');
    }

}