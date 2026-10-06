<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'item_id',
        'pricing_rule_id',
        'type',
        'quantity',
        'subtotal',
        'booking_date',
        'start_time',
        'end_time',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function pricingRule()
    {
        return $this->belongsTo(ItemPricingRule::class, 'pricing_rule_id');
    }
}