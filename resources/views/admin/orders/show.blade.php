@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto mt-8">
    
    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border-r-4 border-red-500 text-red-800 rounded-lg font-bold shadow-sm">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as$error)
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
                    @foreach($item->pricingRules as$rule)
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
                                        <th class="p-2 border">من تاريخ / وقت</th>
                                        <th class="p-2 border">إلى تاريخ / وقت</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($upcomingBookings as$b)
                                    <tr class="bg-white/50">
                                        <td class="p-2 border font-bold text-red-600" dir="ltr">
                                            {{ \Carbon\Carbon::parse($b->booking_date)->format('Y-m-d') }} {{ \Carbon\Carbon::parse($b->start_time)->format('h:i A') }}
                                        </td>
                                        <td class="p-2 border font-bold text-red-600" dir="ltr">
                                            {{ \Carbon\Carbon::parse($b->end_date ?? $b->booking_date)->format('Y-m-d') }} {{ $b->end_time ? \Carbon\Carbon::parse($b->end_time)->format('h:i A') : '05:00 PM' }}
                                        </td>
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
            @if($item->type === 'sale' &&$item->stock_quantity <= 0)
                <div class="h-full flex flex-col items-center justify-center text-center p-6">
                    <div class="w-20 h-20 bg-red-100 text-red-600 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h2 class="text-2xl font-black text-red-600 mb-2">نفذت الكمية</h2>
                    <p class="text-gray-500 font-semibold">عذراً، هذا المنتج غير متوفر في المخزون حالياً.</p>
                </div>
            @else
                <h2 class="text-xl font-bold text-gray-900 mb-6 border-b pb-2">تفاصيل الطلب</h2>
                
                <form id="add-to-cart-form" onsubmit="addToCart(event)" class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">نظام التسعير والوحدة <span class="text-red-500">*</span></label>
                        <select id="pricing_rule_id" required class="w-full p-2 border border-gray-300 rounded-lg">
                            <option value="" data-price="0" data-unit="" data-min="0">-- اختر الوحدة --</option>
                            @foreach($item->pricingRules as$rule)
                                <option value="{{ $rule->id }}" 
                                        data-price="{{ $rule->price }}" 
                                        data-unit="{{ strtolower(trim($rule->unit_type)) }}"
                                        data-min="{{ $rule->min_duration ?? 0 }}">
                                    {{ $rule->unit_type }} ({{$rule->price }} ج.م)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1" id="amount_label">
                            {{ $item->type === 'sale' ? 'الكمية المطلوبة (بعدد الوحدات)' : 'المدة المطلوبة (رقم)' }} <span class="text-red-500">*</span>
                        </label>
                        <input type="number" step="0.5" min="0.5" id="requested_amount" value="1" required class="w-full p-2 border border-gray-300 rounded-lg">
                        @if($item->type === 'sale')
                            <p class="text-xs text-gray-500 mt-1">الكمية المتاحة حالياً: <strong class="text-green-600">{{ $item->stock_quantity }}</strong> وحدات</p>
                        @endif
                    </div>

                    @if($item->type === 'rental')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-white p-4 rounded-lg border border-gray-200 mt-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">تاريخ الحجز <span class="text-red-500">*</span></label>
                            <input type="date" id="booking_date" min="{{ date('Y-m-d') }}" required class="w-full p-2 border border-gray-300 rounded-lg">
                        </div>
                        
                        <div id="start_time_container">
                            <label class="block text-sm font-bold text-gray-700 mb-1">وقت البدء <span class="text-red-500">*</span></label>
                            <input type="time" id="start_time" value="09:00" required class="w-full p-2 border border-gray-300 rounded-lg">
                        </div>

                        <div id="end_time_preview" class="col-span-1 md:col-span-2 p-3 bg-gray-50 rounded-lg text-xs font-bold text-gray-700 border">
                            وقت الانتهاء المتوقع: <span id="calculated_end_datetime" class="text-[var(--color-zu-maroon)]">--</span>
                        </div>

                        <div class="col-span-1 md:col-span-2 text-xs text-gray-500 text-center">
                            * مواعيد العمل الرسمية من 09:00 صباحاً حتى 05:00 مساءً (ما عدا الجمعة).
                        </div>
                    </div>
                    @endif

                    <div class="mt-4 p-4 bg-[var(--color-zu-blue)]/5 border border-[var(--color-zu-blue)]/20 rounded-lg text-center">
                        <span class="text-gray-600 font-bold">الإجمالي المتوقع:</span>
                        <span id="calculated_total" class="text-2xl font-black text-[var(--color-zu-maroon)] mx-2">0.00</span>
                        <span class="text-gray-600 font-bold">ج.م</span>
                    </div>

                    <button type="submit" class="w-full mt-6 bg-[var(--color-zu-blue)] text-white py-3 rounded-lg font-black text-lg hover:bg-blue-900 shadow-md transition flex justify-center items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        إضافة إلى سلة المشتريات
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>

<script>
    const dbBookings = @json($upcomingBookings);
    const isRental = "{{ $item->type }}" === "rental";

    function isHourlyUnit(unit) {
        return ['hr', 'hour', 'hours', 'ساعة', 'ساعات'].includes(unit.toLowerCase());
    }

    function isDailyUnit(unit) {
        return ['day', 'one day', 'days', 'يوم', 'أيام'].includes(unit.toLowerCase());
    }

    function isMonthlyUnit(unit) {
        return ['month', 'months', 'شهر', 'أشهر'].includes(unit.toLowerCase());
    }

    function formatLocalDateTime(dateObj) {
        if (!dateObj) return null;
        const pad = (n) => String(n).padStart(2, '0');
        return `${dateObj.getFullYear()}-${pad(dateObj.getMonth() + 1)}-${pad(dateObj.getDate())} ${pad(dateObj.getHours())}:${pad(dateObj.getMinutes())}:00`;
    }

    function updateUI() {
        const ruleSelect = document.getElementById('pricing_rule_id');
        if (!ruleSelect) return;

        const selectedOption = ruleSelect.options[ruleSelect.selectedIndex];
        const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
        const unit = selectedOption.getAttribute('data-unit') || '';
        const amountInput = document.getElementById('requested_amount');
        let amount = parseFloat(amountInput ? amountInput.value : 1) || 1;

        if (amount < 0.5) amount = 0.5;

        const total = price * amount;
        document.getElementById('calculated_total').textContent = total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

        if (!isRental) return;

        const startTimeInput = document.getElementById('start_time');
        const bookingDateInput = document.getElementById('booking_date');
        const endPreview = document.getElementById('calculated_end_datetime');

        if (isDailyUnit(unit) || isMonthlyUnit(unit)) {
            startTimeInput.value = "09:00";
            startTimeInput.disabled = true;
        } else {
            startTimeInput.disabled = false;
        }

        if (bookingDateInput.value && startTimeInput.value) {
            const calculated = calculateEndTimes(bookingDateInput.value, startTimeInput.value, unit, amount);
            if (calculated) {
                endPreview.textContent = `${calculated.endDate} في تمام الساعة ${calculated.endTimeFormatted}`;
            } else {
                endPreview.textContent = "--";
            }
        }
    }

    function calculateEndTimes(startDateStr, startTimeStr, unit, amount) {
        let start = new Date(`${startDateStr}T${startTimeStr}:00`);
        let end = new Date(start);

        if (isHourlyUnit(unit)) {
            end.setMinutes(end.getMinutes() + (amount * 60));
        } else if (isDailyUnit(unit)) {
            start.setHours(9, 0, 0);
            end = new Date(start);
            end.setDate(end.getDate() + Math.ceil(amount) - 1);
            end.setHours(17, 0, 0);
        } else if (isMonthlyUnit(unit)) {
            start.setHours(9, 0, 0);
            end = new Date(start);
            end.setMonth(end.getMonth() + Math.ceil(amount));
            end.setHours(17, 0, 0);
        } else {
            end.setHours(17, 0, 0);
        }

        const formatTime = (dateObj) => {
            let hours = dateObj.getHours();
            let minutes = dateObj.getMinutes();
            let ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12;
            minutes = minutes < 10 ? '0' + minutes : minutes;
            return `${hours}:${minutes} ${ampm}`;
        };

        return {
            startDate: startDateStr,
            startTimeFormatted: formatTime(start),
            endDate: `${end.getFullYear()}-${String(end.getMonth() + 1).padStart(2, '0')}-${String(end.getDate()).padStart(2, '0')}`,
            endTimeFormatted: formatTime(end),
            startObj: start,
            endObj: end
        };
    }

    function checkOverlap(newStart, newEnd) {
        for (let b of dbBookings) {
            let bStartStr = `${b.booking_date}T${b.start_time}`;
            let bEndStr = `${b.end_date || b.booking_date}T${b.end_time || '17:00:00'}`;
            let bStart = new Date(bStartStr);
            let bEnd = new Date(bEndStr);

            if (newStart < bEnd && newEnd > bStart) {
                return `يتعارض مع حجز مسجل مسبقاً من (${b.booking_date} ${b.start_time}) إلى (${b.end_date || b.booking_date} ${b.end_time || '17:00'})`;
            }
        }

        let cart = JSON.parse(localStorage.getItem('pharmacy_cart')) || [];
        for (let item of cart) {
            if (item.item_id === "{{ $item->id }}" && item.start_datetime && item.end_datetime) {
                let cStart = new Date(item.start_datetime.replace(' ', 'T'));
                let cEnd = new Date(item.end_datetime.replace(' ', 'T'));
                if (newStart < cEnd && newEnd > cStart) {
                    return `يتعارض مع جهاز موجود بالفعل في سلة المشتريات الخاصة بك.`;
                }
            }
        }

        return null;
    }

    function addToCart(e) {
        e.preventDefault();

        const ruleSelect = document.getElementById('pricing_rule_id');
        const selectedOption = ruleSelect.options[ruleSelect.selectedIndex];
        
        if (!ruleSelect.value) {
            alert('يرجى اختيار نظام التسعير والوحدة أولاً.');
            return;
        }

        const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
        const unit = selectedOption.getAttribute('data-unit') || '';
        const minDuration = parseFloat(selectedOption.getAttribute('data-min')) || 0;
        const amount = parseFloat(document.getElementById('requested_amount').value) || 0;

        if (minDuration > 0 && amount < minDuration) {
            alert(`الحد الأدنى المطلوب لهذه الوحدة هو ${minDuration}`);
            return;
        }

        let bookingDate = null, startTime = null, endTime = null, endDate = null;
        let startObj = null, endObj = null;

        if (isRental) {
            bookingDate = document.getElementById('booking_date').value;
            startTime = document.getElementById('start_time').value;

            if (!bookingDate || !startTime) {
                alert('يرجى اختيار تاريخ الحجز ووقت البدء.');
                return;
            }

            const calc = calculateEndTimes(bookingDate, startTime, unit, amount);
            startObj = calc.startObj;
            endObj = calc.endObj;

            if (startObj.getDay() === 5) {
                alert('عذراً، يوم الجمعة عطلة رسمية بالكلية.');
                return;
            }

            if (startObj.getHours() < 9 || (endObj.getHours() > 17 || (endObj.getHours() === 17 && endObj.getMinutes() > 0))) {
                alert('المواعيد المتاحة فقط داخل أوقات العمل الرسمية من 09:00 صباحاً حتى 05:00 مساءً.');
                return;
            }

            const overlapError = checkOverlap(startObj, endObj);
            if (overlapError) {
                alert("تعذر إضافة الجهاز للسلة:\n" + overlapError);
                return;
            }

            endDate = calc.endDate;
            startTime = calc.startTimeFormatted;
            endTime = calc.endTimeFormatted;
        }

        const subtotal = price * amount;
        const unitLabel = selectedOption.innerText.split('(')[0].trim();

        const cartItem = {
            item_id: "{{ $item->id }}",
            item_name: "{{ $item->name }}",
            type: "{{ $item->type }}",
            pricing_rule_id: ruleSelect.value,
            unit_type: unitLabel,
            raw_unit: unit,
            quantity: amount,
            subtotal: subtotal,
            booking_date: bookingDate,
            start_time: startTime,
            end_time: endTime,
            end_date: endDate,
            start_datetime: formatLocalDateTime(startObj),
            end_datetime: formatLocalDateTime(endObj)
        };

        let cart = JSON.parse(localStorage.getItem('pharmacy_cart')) || [];
        cart.push(cartItem);
        localStorage.setItem('pharmacy_cart', JSON.stringify(cart));

        window.location.href = "{{ route('cart.index') }}";
    }

    document.addEventListener('DOMContentLoaded', function() {
        const ruleSelect = document.getElementById('pricing_rule_id');
        const amountInput = document.getElementById('requested_amount');
        const bookingDateInput = document.getElementById('booking_date');
        const startTimeInput = document.getElementById('start_time');

        if (ruleSelect) ruleSelect.addEventListener('change', updateUI);
        if (amountInput) amountInput.addEventListener('input', updateUI);
        if (bookingDateInput) bookingDateInput.addEventListener('change', updateUI);
        if (startTimeInput) startTimeInput.addEventListener('change', updateUI);

        updateUI();
    });
</script>
@endsection