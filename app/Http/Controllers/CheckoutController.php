<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Booking;
use App\Models\Item;
use App\Models\ItemPricingRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CheckoutController extends Controller
{
    public function showCart()
    {
        return view('cart.index');
    }

    public function processCheckout(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_national_id' => 'required|string|size:14',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'required|email|max:255',
            'cart_items' => 'required|array|min:1',
        ]);

        DB::beginTransaction();

        try {
            $totalOrderAmount = 0;
            $referenceNumber = 'ZU-' . strtoupper(Str::random(8));

            $order = Order::create([
                'reference_number' => $referenceNumber,
                'customer_name' => $validated['customer_name'],
                'customer_national_id' => $validated['customer_national_id'],
                'customer_phone' => $validated['customer_phone'],
                'customer_email' => $validated['customer_email'],
                'total_amount' => 0,
                'status' => 'pending',
                'expires_at' => now()->addHours(48),
            ]);

            foreach ($validated['cart_items'] as $index => $cartItem) {
                $item = Item::where('id', $cartItem['item_id'])->lockForUpdate()->firstOrFail();
                $pricingRule = ItemPricingRule::where('id', $cartItem['pricing_rule_id'])->firstOrFail();

                $quantity = (float) $cartItem['quantity'];
                $lineTotal = $pricingRule->price * $quantity;
                $totalOrderAmount += $lineTotal;

                $itemReference = count($validated['cart_items']) > 1 
                    ? "{$referenceNumber}-" . ($index + 1) 
                    : $referenceNumber;

                if ($item->type === 'sale') {
                    if ($quantity > $item->stock_quantity) {
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => "الكمية المطلوبة من {$item->name} غير متوفرة بالمخزون."
                        ], 422);
                    }
                    $item->stock_quantity -= $quantity;
                    $item->save();

                    Booking::create([
                        'reference_number' => $itemReference,
                        'order_id' => $order->id,
                        'item_id' => $item->id,
                        'pricing_rule_id' => $pricingRule->id,
                        'customer_name' => $validated['customer_name'],
                        'customer_national_id' => $validated['customer_national_id'],
                        'customer_phone' => $validated['customer_phone'],
                        'customer_email' => $validated['customer_email'],
                        'requested_amount' => $quantity,
                        'total_price' => $lineTotal,
                        'status' => 'pending',
                        'expires_at' => now()->addHours(48),
                    ]);

                } elseif ($item->type === 'rental') {
                    $bookingDate = $cartItem['booking_date'];
                    $unit = strtolower(trim($cartItem['raw_unit'] ?? $pricingRule->unit_type));

                    $startTimeFormatted = date('H:i:s', strtotime($cartItem['start_time']));
                    $startDateTime = Carbon::parse("{$bookingDate} {$startTimeFormatted}");

                    if ($startDateTime->isFriday()) {
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => "لا يمكن الحجز يوم الجمعة للجهاز {$item->name}."
                        ], 422);
                    }

                    $totalWorkingDays = 0;
                    $isRentalPeriod = false;

                    if (str_contains($unit, 'week') || str_contains($unit, 'أسبوع') || str_contains($unit, 'اسبوع')) {
                        $totalWorkingDays = (int)$quantity * 7; 
                        $isRentalPeriod = true;
                    } elseif (str_contains($unit, 'month') || str_contains($unit, 'شهر') || str_contains($unit, 'أشهر')) {
                        $totalWorkingDays = (int)$quantity * 30; 
                        $isRentalPeriod = true;
                    } elseif (str_contains($unit, 'day') || str_contains($unit, 'يوم') || str_contains($unit, 'أيام')) {
                        $totalWorkingDays = (int)$quantity; 
                        $isRentalPeriod = true;
                    }

                    if ($isRentalPeriod) {
                        $endDateTime = $startDateTime->copy();
                        $addedDays = 0;
                        $targetAdditionalDays = $totalWorkingDays - 1;
                        
                        while ($addedDays < $targetAdditionalDays) {
                            $endDateTime->addDay();
                            if (!$endDateTime->isFriday()) {
                                $addedDays++;
                            }
                        }
                        
                        if ($endDateTime->isFriday()) {
                            $endDateTime->addDay();
                        }
                        $endDateTime->setTime(17, 0, 0);

                    } else {
                        $endTimeFormatted = date('H:i:s', strtotime($cartItem['end_time']));
                        $endDate = $cartItem['end_date'] ?? $bookingDate;
                        $endDateTime = Carbon::parse("{$endDate} {$endTimeFormatted}");
                    }

                    $overlapping = Booking::where('item_id', $item->id)
                        ->whereIn('status', ['pending', 'paid'])
                        ->where(function ($query) use ($startDateTime, $endDateTime) {
                            $query->where('start_time', '<', $endDateTime->format('H:i:s'))
                                  ->where('end_time', '>', $startDateTime->format('H:i:s'))
                                  ->where('booking_date', '<=', $endDateTime->toDateString())
                                  ->where(DB::raw("COALESCE(end_date, booking_date)"), '>=', $startDateTime->toDateString());
                        })->exists();

                    if ($overlapping) {
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => "توقيت حجز الجهاز {$item->name} يتعارض مع حجز آخر مسجل بالفعل."
                        ], 422);
                    }

                    Booking::create([
                        'reference_number' => $itemReference,
                        'order_id' => $order->id,
                        'item_id' => $item->id,
                        'pricing_rule_id' => $pricingRule->id,
                        'customer_name' => $validated['customer_name'],
                        'customer_national_id' => $validated['customer_national_id'],
                        'customer_phone' => $validated['customer_phone'],
                        'customer_email' => $validated['customer_email'],
                        'requested_amount' => $quantity,
                        'total_price' => $lineTotal,
                        'booking_date' => $startDateTime->toDateString(),
                        'end_date' => $endDateTime->toDateString(),
                        'start_time' => $startDateTime->format('H:i:s'),
                        'end_time' => $endDateTime->format('H:i:s'),
                        'status' => 'pending',
                        'expires_at' => now()->addHours(48),
                    ]);
                }
            }

            $order->update(['total_amount' => $totalOrderAmount]);

            DB::commit();

            return response()->json([
                'success' => true,
                'redirect_url' => route('order.success', $order->id)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ غير متوقع أثناء معالجة الطلب: ' . $e->getMessage()
            ], 500);
        }
    }

    public function success($id)
    {
        $order = Order::with(['items.item', 'items.pricingRule'])->findOrFail($id);
        return view('cart.success', compact('order'));
    }
}