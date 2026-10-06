<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_number',
        'customer_name',
        'customer_national_id',
        'customer_phone',
        'customer_email',
        'total_amount',
        'status',
        'expires_at',
    ];

    public function items()
    {
        return $this->hasMany(Booking::class, 'order_id');
    }
}