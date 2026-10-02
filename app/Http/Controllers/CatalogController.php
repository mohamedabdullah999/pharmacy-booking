<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Booking;

class CatalogController extends Controller
{
    public function index(Request $request)
{
    $departments = Department::all();
    
    $query = Item::with(['department', 'pricingRules'])
                 ->where('is_active', true);

    if ($request->filled('department_id')) {
        $query->where('department_id', $request->department_id);
    }

   if ($request->filled('search')) {
       $searchTerm = $request->search;
       $query->whereRaw("MATCH(name, brand, model) AGAINST(? IN BOOLEAN MODE)", [$searchTerm]);
    }

    $items = $query->latest()->get();

    return view('catalog.index', compact('items', 'departments'));
}

    public function show($id)
    {
        $item = Item::with('pricingRules')->findOrFail($id);
        
        $upcomingBookings = collect();
        if ($item->type === 'rental') {
            $upcomingBookings = Booking::where('item_id', $item->id)
                ->whereIn('status', ['pending', 'paid'])
                ->where('booking_date', '>=', now()->toDateString()) 
                ->orderBy('booking_date')
                ->orderBy('start_time')
                ->get();
        }

        return view('catalog.show', compact('item', 'upcomingBookings'));
    }
}