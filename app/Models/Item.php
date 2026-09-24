<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    protected $guarded = [];
    
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function pricingRules(): HasMany
    {
        return $this->hasMany(ItemPricingRule::class);
    }
}
