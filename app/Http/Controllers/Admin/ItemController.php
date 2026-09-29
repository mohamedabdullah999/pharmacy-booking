<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Department;
use App\Models\ItemPricingRule;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::with('department');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%");
        }

        $items = $query->latest()->paginate(10);
        return view('admin.items.index', compact('items'));
    }

    public function create()
    {
        $departments = Department::all();
        return view('admin.items.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'type' => 'required|in:sale,rental',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'stock_quantity' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'pricing' => 'required|array|min:1',
            'pricing.*.unit_type' => 'required|string|max:50',
            'pricing.*.price' => 'required|numeric|min:0',
            'pricing.*.min_duration' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $imagePath = null;
            if ($validated['type'] === 'rental' && $request->hasFile('image')) {
                $imagePath = $request->file('image')->store('items', 'public');
            }

            $item = Item::create([
                'name' => $validated['name'],
                'department_id' => $validated['department_id'],
                'type' => $validated['type'],
                'brand' => $validated['brand'],
                'model' => $validated['model'],
                'stock_quantity' => $validated['type'] === 'sale' ? ($validated['stock_quantity'] ?? 0) : 0,
                'is_active' => $request->has('is_active'),
                'image' => $imagePath,
            ]);

            foreach ($validated['pricing'] as $rule) {
                ItemPricingRule::create([
                    'item_id' => $item->id,
                    'unit_type' => $rule['unit_type'],
                    'price' => $rule['price'],
                    'min_duration' => $rule['min_duration'] ?? null,
                ]);
            }

            DB::commit();

            return redirect()->route('admin.items.index')->with('success', 'تمت إضافة العنصر بنجاح!');
        } catch (\Exception $e) {
            DB::rollBack();
            if (isset($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            return back()->withErrors(['error' => 'حدث خطأ أثناء الحفظ: ' . $e->getMessage()])->withInput();
        }
    }

    public function edit($id)
    {
        $item = Item::with('pricingRules')->findOrFail($id);
        $departments = Department::all();
        return view('admin.items.edit', compact('item', 'departments'));
    }

    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'type' => 'required|in:sale,rental',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'stock_quantity' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'pricing' => 'required|array|min:1',
            'pricing.*.id' => 'nullable|exists:item_pricing_rules,id',
            'pricing.*.unit_type' => 'required|string|max:50',
            'pricing.*.price' => 'required|numeric|min:0',
            'pricing.*.min_duration' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            if ($validated['type'] === 'rental') {
                if ($request->hasFile('image')) {
                    if ($item->image) {
                        Storage::disk('public')->delete($item->image);
                    }
                    $item->image = $request->file('image')->store('items', 'public');
                }
            } else {
                if ($item->image) {
                    Storage::disk('public')->delete($item->image);
                    $item->image = null;
                }
            }

            $item->update([
                'name' => $validated['name'],
                'department_id' => $validated['department_id'],
                'type' => $validated['type'],
                'brand' => $validated['brand'],
                'model' => $validated['model'],
                'stock_quantity' => $validated['type'] === 'sale' ? ($validated['stock_quantity'] ?? 0) : 0,
                'is_active' => $request->has('is_active'),
            ]);

            $submittedRuleIds = [];
            foreach ($validated['pricing'] as $ruleData) {
                if (isset($ruleData['id'])) {
                    $rule = ItemPricingRule::findOrFail($ruleData['id']);
                    $rule->update([
                        'unit_type' => $ruleData['unit_type'],
                        'price' => $ruleData['price'],
                        'min_duration' => $ruleData['min_duration'] ?? null,
                    ]);
                    $submittedRuleIds[] = $rule->id;
                } else {
                    $newRule = ItemPricingRule::create([
                        'item_id' => $item->id,
                        'unit_type' => $ruleData['unit_type'],
                        'price' => $ruleData['price'],
                        'min_duration' => $ruleData['min_duration'] ?? null,
                    ]);
                    $submittedRuleIds[] = $newRule->id;
                }
            }

            $rulesToDelete = $item->pricingRules()->whereNotIn('id', $submittedRuleIds)->get();
            foreach ($rulesToDelete as $ruleToDelete) {
                $isUsed = Booking::where('pricing_rule_id', $ruleToDelete->id)->exists();
                if ($isUsed) {
                    throw new \Exception("لا يمكن حذف وحدة التسعير ({$ruleToDelete->unit_type}) لوجود حجوزات سابقة مرتبطة بها.");
                }
                $ruleToDelete->delete();
            }

            DB::commit();

            return redirect()->route('admin.items.index')->with('success', 'تم تعديل بيانات العنصر بنجاح!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'خطأ أثناء التعديل: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy(Item $item)
    {
        if ($item->bookings()->exists()) {
            return back()->withErrors(['error' => 'لا يمكن حذف هذا العنصر لوجود طلبات وحجوزات مرتبطة به. يمكنك تعطيله (Inactive) بدلاً من ذلك.']);
        }
        
        $item->delete();
        return redirect()->route('admin.items.index')->with('success', 'تم حذف العنصر بنجاح.');
    }
}