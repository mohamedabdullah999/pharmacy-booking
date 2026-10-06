<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

   protected $fillable = [
    'reference_number',
    'order_id', 
    'item_id',
    'pricing_rule_id',
    'customer_name',
    'customer_national_id',
    'customer_phone',
    'customer_email',
    'requested_amount',
    'total_price',
    'booking_date',
    'end_date',
    'start_time',
    'end_time',
    'status',
    'expires_at',
];
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function pricingRule()
    {
        return $this->belongsTo(ItemPricingRule::class, 'pricing_rule_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}