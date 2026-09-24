@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mt-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-0">
        
        <div class="p-8 bg-gray-50 border-l border-gray-200">
            <div class="inline-block px-3 py-1 mb-4 text-xs font-bold text-white rounded-full {{ $item->type === 'sale' ? 'bg-[var(--color-zu-green)]' : 'bg-[var(--color-zu-blue)]' }}">
                {{ $item->type === 'sale' ? 'مستلزمات للبيع' : 'جهاز للإيجار' }}
            </div>
            
            <h2 class="text-3xl font-black text-gray-900 mb-2">{{ $item->name }}</h2>
            <p class="text-lg text-[var(--color-zu-maroon)] font-bold mb-6">{{ $item->department->name }}</p>

            @if($item->type === 'rental')
                <div class="w-full h-64 bg-white rounded-xl shadow-inner flex items-center justify-center overflow-hidden mb-6 border border-gray-200">
                    @if($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                    @else
                        <svg class="w-20 h-20 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    @endif
                </div>
            @endif

            @if($item->brand || $item->model)
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 space-y-2 text-sm text-gray-700">
                    @if($item->brand) <p><strong>الشركة المصنعة:</strong> {{ $item->brand }}</p> @endif
                    @if($item->model) <p><strong>الموديل:</strong> {{ $item->model }}</p> @endif
                </div>
            @endif
        </div>

        <div class="p-8">
            <h3 class="text-xl font-bold text-[var(--color-zu-blue)] mb-6 border-b pb-2">تفاصيل الطلب</h3>
            
            <form action="#" method="POST" id="booking-form">
                @csrf
                
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-3">اختر نظام التسعير:</label>
                    <div class="space-y-3">
                        @foreach($item->pricingRules as $index =>$rule)
                            <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-blue-50 transition border-gray-200 rule-option">
                                <input type="radio" name="pricing_rule_id" value="{{ $rule->id }}" 
                                       data-price="{{ $rule->price }}" 
                                       data-unit="{{ $rule->unit_type }}" 
                                       data-min="{{ $rule->min_duration ?? 1 }}"
                                       class="w-5 h-5 text-[var(--color-zu-blue)] focus:ring-[var(--color-zu-blue)]"
                                       {{ $index === 0 ? 'checked' : '' }}>
                                <span class="mr-3 font-semibold text-gray-800">
                                    {{ $rule->price }} جنيه / {{ $rule->unit_type }}
                                    @if($rule->min_duration) <span class="text-xs text-gray-500 block">(حد أدنى: {{ $rule->min_duration }})</span> @endif
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        {{ $item->type === 'sale' ? 'الكمية المطلوبة' : 'المدة المطلوبة' }} (<span id="unit-label"></span>):
                    </label>
                    <div class="flex items-center">
                        <button type="button" id="btn-decrease" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-r-lg font-bold hover:bg-gray-300 transition">-</button>
                        <input type="number" name="requested_amount" id="requested-amount" value="1" step="0.5" class="w-20 text-center border-y border-gray-200 py-2 focus:outline-none focus:ring-0">
                        <button type="button" id="btn-increase" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-l-lg font-bold hover:bg-gray-300 transition">+</button>
                    </div>
                    <p id="error-msg" class="text-red-500 text-xs mt-2 hidden"></p>
                </div>

                <div class="space-y-4 mb-6 border-t pt-6">
                    <h4 class="font-bold text-gray-700">بيانات الطالب</h4>
                    <div>
                        <input type="text" name="customer_name" placeholder="الاسم الرباعي" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-[var(--color-zu-blue)] focus:border-[var(--color-zu-blue)]">
                    </div>
                    <div>
                        <input type="text" name="customer_national_id" placeholder="الرقم القومي (14 رقم)" required pattern="[0-9]{14}" title="يجب إدخال 14 رقم" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-[var(--color-zu-blue)] focus:border-[var(--color-zu-blue)]">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <input type="text" name="customer_phone" placeholder="رقم الهاتف" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-[var(--color-zu-blue)] focus:border-[var(--color-zu-blue)]">
                        <input type="email" name="customer_email" placeholder="البريد الإلكتروني" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-[var(--color-zu-blue)] focus:border-[var(--color-zu-blue)]">
                    </div>
                </div>

                <div class="bg-[var(--color-zu-beige)] p-4 rounded-xl flex justify-between items-center mb-6 shadow-sm border border-[var(--color-zu-maroon)]/20">
                    <span class="font-bold text-gray-700">التكلفة الإجمالية:</span>
                    <span class="text-2xl font-black text-[var(--color-zu-maroon)]"><span id="total-price">0</span> جنيه</span>
                </div>

                <button type="submit" class="w-full bg-[var(--color-zu-blue)] text-white py-4 rounded-xl font-black text-lg hover:bg-[#002244] shadow-lg hover:shadow-xl transition duration-300">
                    تأكيد الحجز والدفع بالخزينة
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const radios = document.querySelectorAll('input[name="pricing_rule_id"]');
        const amountInput = document.getElementById('requested-amount');
        const btnIncrease = document.getElementById('btn-increase');
        const btnDecrease = document.getElementById('btn-decrease');
        const totalPriceEl = document.getElementById('total-price');
        const unitLabelEl = document.getElementById('unit-label');
        const errorMsgEl = document.getElementById('error-msg');

        function calculateTotal() {
            const selectedRadio = document.querySelector('input[name="pricing_rule_id"]:checked');
            if (!selectedRadio) return;

            const price = parseFloat(selectedRadio.dataset.price);
            const minDuration = parseFloat(selectedRadio.dataset.min);
            const unit = selectedRadio.dataset.unit;
            let amount = parseFloat(amountInput.value);

            unitLabelEl.textContent = unit;

            if (amount < minDuration) {
                errorMsgEl.textContent = `الحد الأدنى لهذا الاختيار هو ${minDuration}`;
                errorMsgEl.classList.remove('hidden');
                amountInput.value = minDuration;
                amount = minDuration;
            } else {
                errorMsgEl.classList.add('hidden');
            }

            const total = price * amount;
            totalPriceEl.textContent = total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        radios.forEach(radio => radio.addEventListener('change', calculateTotal));
        amountInput.addEventListener('input', calculateTotal);

        btnIncrease.addEventListener('click', () => {
            amountInput.value = parseFloat(amountInput.value) + 0.5;
            calculateTotal();
        });

        btnDecrease.addEventListener('click', () => {
            const selectedRadio = document.querySelector('input[name="pricing_rule_id"]:checked');
            const minDuration = selectedRadio ? parseFloat(selectedRadio.dataset.min) : 1;
            
            if (parseFloat(amountInput.value) > minDuration) {
                amountInput.value = parseFloat(amountInput.value) - 0.5;
                calculateTotal();
            }
        });

        calculateTotal();
    });
</script>
@endsection