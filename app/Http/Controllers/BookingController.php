<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Booking;
use App\Models\ItemPricingRule;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function store(Request $request, $itemId)
    {
        $validated = $request->validate([
            'pricing_rule_id' => 'required|exists:item_pricing_rules,id',
            'requested_amount' => 'required|numeric|min:0.5',
            'customer_name' => 'required|string|max:255',
            'customer_national_id' => 'required|string|size:14',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'required|email|max:255',
        ]);

        $item = Item::findOrFail($itemId);
        $pricingRule = ItemPricingRule::where('id', $validated['pricing_rule_id'])
                                      ->where('item_id', $item->id)
                                      ->firstOrFail();

        if ($pricingRule->min_duration && $validated['requested_amount'] < $pricingRule->min_duration) {
            return back()->withErrors(['amount' => 'الكمية أو المدة المطلوبة أقل من الحد الأدنى المسموح به.'])->withInput();
        }

        $totalPrice = $pricingRule->price * $validated['requested_amount'];

        $referenceNumber = 'ZU-' . strtoupper(Str::random(8));

        $booking = Booking::create([
            'reference_number' => $referenceNumber,
            'item_id' => $item->id,
            'pricing_rule_id' => $pricingRule->id,
            'customer_name' => $validated['customer_name'],
            'customer_national_id' => $validated['customer_national_id'],
            'customer_phone' => $validated['customer_phone'],
            'customer_email' => $validated['customer_email'],
            'requested_amount' => $validated['requested_amount'],
            'total_price' => $totalPrice,
            'status' => 'pending',
            'expires_at' => now()->addHours(48), 
        ]);

        return redirect()->route('booking.success', $booking->reference_number);
    }

    public function success($reference)
    {
        $booking = Booking::with('item')->where('reference_number', $reference)->firstOrFail();
        return view('catalog.success', compact('booking'));
    }
}