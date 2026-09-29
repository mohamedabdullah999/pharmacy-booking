@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto mt-8">
    
    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border-r-4 border-red-500 text-red-800 rounded-lg font-bold shadow-sm">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden flex flex-col md:flex-row">
        
        <div class="p-8 w-full md:w-1/2 border-l border-gray-100">
            <div class="mb-2">
                @if($item->type === 'sale')
                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-bold">مستلزمات (بيع)</span>
                @else
                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-bold">جهاز (إيجار)</span>
                @endif
            </div>
            
            <h1 class="text-3xl font-black text-[var(--color-zu-blue)] mb-4">{{ $item->name }}</h1>
            
            <div class="mb-6">
                <h3 class="text-gray-500 text-sm font-bold mb-2">قواعد التسعير المتاحة:</h3>
                <ul class="space-y-2">
                    @foreach($item->pricingRules as $rule)
                        <li class="bg-gray-50 p-3 rounded-lg border border-gray-200 text-sm">
                            <strong class="text-[var(--color-zu-maroon)]">{{ $rule->price }} ج.م</strong> 
                            لكل ({{ $rule->unit_type }})
                            @if($rule->min_duration)
                                <span class="text-gray-500 text-xs block mt-1">- الحد الأدنى للطلب: {{ (float)$rule->min_duration }}</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
            
            @if($item->type === 'rental')
                <div class="mt-8 bg-blue-50/50 p-4 rounded-xl border border-blue-100">
                    <h3 class="text-md font-bold text-[var(--color-zu-blue)] mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        الأوقات المحجوزة مسبقاً (غير متاحة)
                    </h3>
                    
                    @if($upcomingBookings->isEmpty())
                        <p class="text-sm text-green-600 font-bold">الجهاز متاح بالكامل حالياً، لا توجد حجوزات قادمة.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-right text-xs">
                                <thead class="bg-white text-gray-500">
                                    <tr>
                                        <th class="p-2 border">التاريخ</th>
                                        <th class="p-2 border">من</th>
                                        <th class="p-2 border">إلى</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($upcomingBookings as $b)
                                    <tr class="bg-white/50">
                                        <td class="p-2 border font-bold" dir="ltr">{{ \Carbon\Carbon::parse($b->booking_date)->format('Y-m-d') }}</td>
                                        <td class="p-2 border text-red-600 font-bold" dir="ltr">{{ \Carbon\Carbon::parse($b->start_time)->format('h:i A') }}</td>
                                        <td class="p-2 border text-red-600 font-bold" dir="ltr">{{ $b->end_time ? \Carbon\Carbon::parse($b->end_time)->format('h:i A') : 'غير محدد' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <p class="text-[10px] text-gray-500 mt-2">* يرجى تجنب اختيار هذه الأوقات أثناء الحجز لتجنب رفض الطلب.</p>
                    @endif
                </div>
            @endif
        </div>

        <div class="p-8 w-full md:w-1/2 bg-gray-50">
            
            @if($item->type === 'sale' && $item->stock_quantity <= 0)
                <div class="h-full flex flex-col items-center justify-center text-center p-6">
                    <div class="w-20 h-20 bg-red-100 text-red-600 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h2 class="text-2xl font-black text-red-600 mb-2">نفذت الكمية</h2>
                    <p class="text-gray-500 font-semibold">عذراً، هذا المنتج غير متوفر في المخزون حالياً. يرجى المحاولة في وقت لاحق.</p>
                </div>
            @else
                <h2 class="text-xl font-bold text-gray-900 mb-6 border-b pb-2">طلب حجز / شراء</h2>
                
                <form action="{{ route('booking.store', $item->id) }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">الاسم الرباعي <span class="text-red-500">*</span></label>
                        <input type="text" name="customer_name" value="{{ old('customer_name') }}" required class="w-full p-2 border border-gray-300 rounded-lg focus:ring-[var(--color-zu-blue)] focus:border-[var(--color-zu-blue)]">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">الرقم القومي <span class="text-red-500">*</span></label>
                            <input type="text" name="customer_national_id" value="{{ old('customer_national_id') }}" required maxlength="14" class="w-full p-2 border border-gray-300 rounded-lg text-left" dir="ltr">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">رقم الهاتف <span class="text-red-500">*</span></label>
                            <input type="text" name="customer_phone" value="{{ old('customer_phone') }}" required class="w-full p-2 border border-gray-300 rounded-lg text-left" dir="ltr">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">البريد الإلكتروني <span class="text-red-500">*</span></label>
                        <input type="email" name="customer_email" value="{{ old('customer_email') }}" required class="w-full p-2 border border-gray-300 rounded-lg text-left" dir="ltr">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">نظام التسعير والوحدة <span class="text-red-500">*</span></label>
                        <select name="pricing_rule_id" id="pricing_rule_id" required class="w-full p-2 border border-gray-300 rounded-lg">
                            <option value="" data-price="0" data-unit="">-- اختر الوحدة --</option>
                            @foreach($item->pricingRules as $rule)
                                <option value="{{ $rule->id }}" data-price="{{ $rule->price }}" data-unit="{{ strtolower(trim($rule->unit_type)) }}" {{ old('pricing_rule_id') == $rule->id ? 'selected' : '' }}>
                                    {{ $rule->unit_type }} ({{ $rule->price }} ج.م)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">
                            {{ $item->type === 'sale' ? 'الكمية المطلوبة (بعدد الوحدات)' : 'المدة المطلوبة (رقم)' }} <span class="text-red-500">*</span>
                        </label>
                        <input type="number" step="0.1" name="requested_amount" id="requested_amount" value="{{ old('requested_amount', 1) }}" required class="w-full p-2 border border-gray-300 rounded-lg">
                        @if($item->type === 'sale')
                            <p class="text-xs text-gray-500 mt-1">الكمية المتاحة حالياً: <strong class="text-green-600">{{ $item->stock_quantity }}</strong> وحدات</p>
                        @endif
                    </div>

                    @if($item->type === 'rental')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-white p-4 rounded-lg border border-gray-200 mt-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">تاريخ الحجز <span class="text-red-500">*</span></label>
                            <input type="date" name="booking_date" value="{{ old('booking_date') }}" min="{{ date('Y-m-d') }}" required class="w-full p-2 border border-gray-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">وقت البدء <span class="text-red-500">*</span></label>
                            <input type="time" name="start_time" value="{{ old('start_time') }}" required class="w-full p-2 border border-gray-300 rounded-lg">
                        </div>
                        
                        <div id="end_time_container" class="col-span-1 md:col-span-2 hidden">
                            <label class="block text-sm font-bold text-gray-700 mb-1">وقت الانتهاء المتوقع (لأن الحجز ليس بالساعة) <span class="text-red-500">*</span></label>
                            <input type="time" name="end_time" id="end_time_input" value="{{ old('end_time') }}" class="w-full p-2 border border-gray-300 rounded-lg">
                        </div>

                        <div class="col-span-1 md:col-span-2 text-xs text-gray-500 text-center mt-1">
                            * مواعيد العمل من 9 صباحاً حتى 5 مساءً (ما عدا الجمعة)
                        </div>
                    </div>
                    @endif

                    <div class="mt-4 p-4 bg-[var(--color-zu-blue)]/5 border border-[var(--color-zu-blue)]/20 rounded-lg text-center">
                        <span class="text-gray-600 font-bold">الإجمالي المتوقع:</span>
                        <span id="calculated_total" class="text-2xl font-black text-[var(--color-zu-maroon)] mx-2">0.00</span>
                        <span class="text-gray-600 font-bold">ج.م</span>
                    </div>

                    <button type="submit" class="w-full mt-6 bg-[var(--color-zu-maroon)] text-white py-3 rounded-lg font-black text-lg hover:bg-[#600018] shadow-md transition flex justify-center items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        تأكيد الطلب المبدئي (للدفع بالخزينة)
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ruleSelect = document.getElementById('pricing_rule_id');
        const amountInput = document.getElementById('requested_amount');
        const totalDisplay = document.getElementById('calculated_total');
        const endTimeContainer = document.getElementById('end_time_container');
        const endTimeInput = document.getElementById('end_time_input');

        function updateUI() {
            if (!ruleSelect) return;
            
            const selectedOption = ruleSelect.options[ruleSelect.selectedIndex];
            const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
            const unit = selectedOption.getAttribute('data-unit') || '';
            const amount = parseFloat(amountInput ? amountInput.value : 0) || 0;
            
            if (totalDisplay) {
                const total = price * amount;
                totalDisplay.textContent = total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            }

            if (endTimeContainer && endTimeInput) {
                const isHourly = ['hr', 'hour', 'ساعة', 'ساعات'].includes(unit.toLowerCase());
                
                if (unit === '' || isHourly) {
                    endTimeContainer.classList.add('hidden');
                    endTimeInput.removeAttribute('required');
                } else {
                    endTimeContainer.classList.remove('hidden');
                    endTimeInput.setAttribute('required', 'required');
                }
            }
        }

        if (ruleSelect) ruleSelect.addEventListener('change', updateUI);
        if (amountInput) amountInput.addEventListener('input', updateUI);

        updateUI(); 
    });
</script>
@endsection