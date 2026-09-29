<?php

namespace App\Console\Commands;

use App\Models\Booking;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CancelExpiredBookings extends Command
{
    protected $signature = 'bookings:cancel-expired';
    protected $description = 'Cancel pending bookings older than 24 hours and restore item stock.';

    public function handle()
    {
        $expiredBookings = Booking::with('item')
            ->where('status', 'pending')
            ->where('expires_at', '<', now())
            ->get();

        foreach ($expiredBookings as $booking) {
            DB::transaction(function () use ($booking) {
                $booking->update(['status' => 'expired']);

                if ($booking->item && $booking->item->type === 'sale') {
                    $booking->item->increment('stock_quantity', $booking->requested_amount);
                }
            });
        }

        $this->info(count($expiredBookings) . ' expired bookings cancelled and stock restored.');
    }
}