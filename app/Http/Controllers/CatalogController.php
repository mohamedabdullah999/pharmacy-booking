<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Booking;
use Illuminate\Support\Facades\Schema;

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
        $item = Item::with('pricingRules', 'department')->findOrFail($id);

        $hasEndDate = Schema::hasColumn('bookings', 'end_date');

        $upcomingBookings = Booking::where('item_id', $item->id)
            ->whereIn('status', ['pending', 'paid'])
            ->where(function($q) use ($hasEndDate) {
                $q->where('booking_date', '>=', now()->toDateString());
                if ($hasEndDate) {
                    $q->orWhere(function($q2) {
                        $q2->whereNotNull('end_date')
                           ->where('end_date', '>=', now()->toDateString());
                    });
                }
            })
            ->orderBy('booking_date')
            ->orderBy('start_time')
            ->get();

        return view('catalog.show', compact('item', 'upcomingBookings'));
    }
}