<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    protected $guarded = [];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function pricingRule(): BelongsTo
    {
        return $this->belongsTo(ItemPricingRule::class);
    }
}