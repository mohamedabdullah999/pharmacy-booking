<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Booking;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'total_items' => Item::count(),
            'total_bookings' => Booking::count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'paid_bookings' => Booking::where('status', 'paid')->count(),
            'expired_bookings' => Booking::where('status', 'expired')->count(), 
        ];

        $recentBookings = Booking::with('item')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentBookings'));
    }
}