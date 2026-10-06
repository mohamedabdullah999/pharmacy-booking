@extends('layouts.admin')

@section('title', __('تعديل بيانات العنصر'))

@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden text-start">
    
    <div class="p-6 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
        <h2 class="text-xl font-bold text-[var(--color-zu-blue)]">{{ __('تعديل: ') }}{{ $item->name }}</h2>
        <a href="{{ route('admin.items.index') }}" class="text-gray-500 hover:text-gray-800 font-bold text-sm">{!! __('العودة للقائمة &larr;') !!}</a>
    </div>

    @if($errors->any())
        <div class="p-4 bg-red-50 border-s-4 border-red-500 text-red-700 m-6 rounded-e-lg text-sm font-bold shadow-sm">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.items.update', $item->id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-8">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('اسم العنصر') }} <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $item->name) }}" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-[var(--color-zu-blue)]">
            </div>
            
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('القسم المعملي') }} <span class="text-red-500">*</span></label>
                <select name="department_id" required class="w-full p-3 border border-gray-300 rounded-lg bg-white">
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ old('department_id', $item->department_id) == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('الشركة المصنعة') }}</label>
                <input type="text" name="brand" value="{{ old('brand', $item->brand) }}" class="w-full p-3 border border-gray-300 rounded-lg">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('الموديل') }}</label>
                <input type="text" name="model" value="{{ old('model', $item->model) }}" class="w-full p-3 border border-gray-300 rounded-lg">
            </div>
        </div>

        <hr class="border-gray-100">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('نوع العنصر') }} <span class="text-red-500">*</span></label>
                <select name="type" id="item-type" required class="w-full p-3 border border-gray-300 rounded-lg bg-white">
                    <option value="rental" {{ old('type', $item->type) == 'rental' ? 'selected' : '' }}>{{ __('جهاز (للإيجار)') }}</option>
                    <option value="sale" {{ old('type', $item->type) == 'sale' ? 'selected' : '' }}>{{ __('مستلزمات (للبيع)') }}</option>
                </select>
            </div>

            <div id="stock-container" class="hidden">
                <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('الكمية المتاحة في المخزن') }} <span class="text-red-500">*</span></label>
                <input type="number" name="stock_quantity" id="stock-input" value="{{ old('stock_quantity', $item->stock_quantity) }}" min="0" class="w-full p-3 border border-gray-300 rounded-lg" dir="ltr">
            </div>

            <div id="image-container" class="md:col-span-2 hidden">
                <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('تحديث الصورة (اتركه فارغاً للاحتفاظ بالصورة الحالية)') }}</label>
                @if($item->image &&$item->type === 'rental')
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $item->image) }}" class="h-20 w-20 object-cover rounded border">
                    </div>
                @endif
                <input type="file" name="image" accept="image/*" class="w-full p-2 border border-gray-300 rounded-lg bg-gray-50 file:me-4 file:py-2 file:px-4 file:rounded-lg file:bg-[var(--color-zu-blue)] file:text-white">
            </div>
        </div>

        <hr class="border-gray-100">

        <div>
            <div class="flex justify-between items-center mb-4">
                <label class="block text-sm font-bold text-[var(--color-zu-maroon)]">{{ __('قواعد التسعير والوحدات') }} <span class="text-red-500">*</span></label>
                <button type="button" id="add-pricing-btn" class="text-sm bg-green-100 text-green-800 px-3 py-1 rounded-lg font-bold">{{ __('+ إضافة وحدة تسعير') }}</button>
            </div>
            
            <div class="bg-gray-50 border border-gray-200 rounded-xl overflow-hidden">
                <table class="w-full text-start text-sm">
                    <thead class="bg-gray-100 text-gray-600 border-b">
                        <tr>
                            <th class="p-3 font-bold text-start">{{ __('الوحدة (hr, day, etc)') }}</th>
                            <th class="p-3 font-bold text-start">{{ __('السعر (ج.م)') }}</th>
                            <th class="p-3 font-bold text-start">{{ __('الحد الأدنى (اختياري)') }}</th>
                            <th class="p-3 font-bold text-center">{{ __('حذف') }}</th>
                        </tr>
                    </thead>
                    <tbody id="pricing-container" class="divide-y divide-gray-100">
                        @foreach($item->pricingRules as $index =>$rule)
                        <tr class="pricing-row bg-white">
                            <input type="hidden" name="pricing[{{ $index }}][id]" value="{{ $rule->id }}">
                            <td class="p-3"><input type="text" name="pricing[{{ $index }}][unit_type]" value="{{ old('pricing.'.$index.'.unit_type',$rule->unit_type) }}" required class="w-full p-2 border border-gray-300 rounded" dir="ltr"></td>
                            <td class="p-3"><input type="number" name="pricing[{{ $index }}][price]" step="0.01" min="0" value="{{ old('pricing.'.$index.'.price',$rule->price) }}" required class="w-full p-2 border border-gray-300 rounded" dir="ltr"></td>
                            <td class="p-3"><input type="number" name="pricing[{{ $index }}][min_duration]" step="0.5" min="0" value="{{ old('pricing.'.$index.'.min_duration',$rule->min_duration) }}" class="w-full p-2 border border-gray-300 rounded" dir="ltr"></td>
                            <td class="p-3 text-center">
                                <button type="button" class="remove-pricing-btn text-red-500 font-bold p-2">X</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="flex items-center gap-3 bg-blue-50 p-4 rounded-xl border border-blue-100">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $item->is_active) ? 'checked' : '' }} id="is_active" class="w-5 h-5 text-[var(--color-zu-blue)] rounded">
            <label for="is_active" class="font-bold text-gray-800 cursor-pointer">{{ __('الجهاز/المنتج متاح للحجز') }}</label>
        </div>

        <button type="submit" class="w-full bg-[var(--color-zu-blue)] text-white py-4 rounded-xl font-black text-lg hover:bg-[#002244] shadow-lg transition">
            {{ __('حفظ التعديلات') }}
        </button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('item-type');
        const stockContainer = document.getElementById('stock-container');
        const stockInput = document.getElementById('stock-input');
        const imageContainer = document.getElementById('image-container'); 
        
        function toggleFields() {
            if (typeSelect.value === 'sale') {
                stockContainer.classList.remove('hidden');
                stockInput.setAttribute('required', 'required');
                imageContainer.classList.add('hidden');
            } else {
                stockContainer.classList.add('hidden');
                stockInput.removeAttribute('required');
                imageContainer.classList.remove('hidden');
            }
        }
        typeSelect.addEventListener('change', toggleFields);
        toggleFields();

        const pricingContainer = document.getElementById('pricing-container');
        const addPricingBtn = document.getElementById('add-pricing-btn');
        let pricingIndex = {{ $item->pricingRules->count() }};

        addPricingBtn.addEventListener('click', function() {
            const row = document.createElement('tr');
            row.className = 'pricing-row bg-white';
            row.innerHTML = `
                <td class="p-3"><input type="text" name="pricing[${pricingIndex}][unit_type]" required class="w-full p-2 border border-gray-300 rounded" dir="ltr"></td>
                <td class="p-3"><input type="number" name="pricing[${pricingIndex}][price]" step="0.01" min="0" required class="w-full p-2 border border-gray-300 rounded" dir="ltr"></td>
                <td class="p-3"><input type="number" name="pricing[${pricingIndex}][min_duration]" step="0.5" min="0" class="w-full p-2 border border-gray-300 rounded" dir="ltr"></td>
                <td class="p-3 text-center"><button type="button" class="remove-pricing-btn text-red-500 font-bold p-2">X</button></td>
            `;
            pricingContainer.appendChild(row);
            pricingIndex++;
            updateRemoveButtons();
        });

        pricingContainer.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-pricing-btn')) {
                e.target.closest('tr').remove();
                updateRemoveButtons();
            }
        });

        function updateRemoveButtons() {
            const btns = document.querySelectorAll('.remove-pricing-btn');
            btns.forEach(btn => btn.classList.toggle('hidden', document.querySelectorAll('.pricing-row').length === 1));
        }
        updateRemoveButtons();
    });
</script>
@endsection