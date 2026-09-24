<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
         $departments = Department::select('id', 'name')->get();
        
         $itemsQuery = Item::with([
            'pricingRules:id,item_id,unit_type,price,min_duration,condition_text',
            'department:id,name'
         ])
         ->where('is_active', true)
         ->select('id', 'department_id', 'name', 'type', 'brand', 'model', 'image');

        if ($request->has('department_id') && $request->department_id != '') {
            $itemsQuery->where('department_id', $request->department_id);
        }

        $items = $itemsQuery->get();

        return view('catalog.index', compact('departments', 'items'));
    }

    public function show($id)
    {
        $item = Item::with([
            'pricingRules:id,item_id,unit_type,price,min_duration,condition_text',
            'department:id,name'
        ])->findOrFail($id);

        if (!$item->is_active) {
            abort(404, 'هذا العنصر غير متاح حالياً.');
        }

        return view('catalog.show', compact('item'));
    }
}