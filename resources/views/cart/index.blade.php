@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-8 px-4">
    <h1 class="text-3xl font-black text-[#00315f] mb-6 text-start">{{ __('سلة المشتريات والحجوزات') }}</h1>

    <div id="cart-empty-msg" class="hidden bg-white p-12 rounded-2xl text-center shadow-sm border border-gray-200">
        <p class="text-gray-500 font-bold text-xl mb-6">{{ __('سلة المشتريات فارغة حالياً.') }}</p>
        <a href="{{ route('catalog.index') }}" class="inline-block bg-[#00315f] text-white px-8 py-3 rounded-xl font-bold hover:bg-blue-900 transition">{{ __('تصفح الأجهزة والمستلزمات') }}</a>
    </div>

    <div id="cart-container" class="grid grid-cols-1 lg:grid-cols-3 gap-8 hidden">
        <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
            <h2 class="text-xl font-bold mb-4 border-b pb-2 text-gray-800 text-start">{{ __('العناصر المختارة') }}</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-start text-sm">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600">
                            <th class="p-3 font-bold text-start">{{ __('العنصر') }}</th>
                            <th class="p-3 font-bold text-start">{{ __('التفاصيل / الموعد') }}</th>
                            <th class="p-3 font-bold text-start">{{ __('التكلفة') }}</th>
                            <th class="p-3 font-bold text-center">{{ __('إجراء') }}</th>
                        </tr>
                    </thead>
                    <tbody id="cart-items-body" class="divide-y divide-gray-100"></tbody>
                </table>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 h-fit text-start">
            <h2 class="text-xl font-bold mb-4 border-b pb-2 text-gray-800">{{ __('بيانات مقدم الطلب') }}</h2>
            <form id="checkout-form" onsubmit="handleCheckout(event)" class="space-y-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">{{ __('الاسم الرباعي *') }}</label>
                    <input type="text" id="customer_name" required class="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#800020] outline-none">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">{{ __('الرقم القومي (14 رقم) *') }}</label>
                    <input type="text" id="customer_national_id" pattern="\d{14}" required class="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#800020] outline-none text-start" dir="ltr">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">{{ __('رقم الهاتف *') }}</label>
                    <input type="text" id="customer_phone" required class="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#800020] outline-none text-start" dir="ltr">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">{{ __('البريد الإلكتروني *') }}</label>
                    <input type="email" id="customer_email" required class="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#800020] outline-none text-start" dir="ltr">
                </div>

                <div class="p-4 bg-gray-50 rounded-xl border border-gray-200 mt-6">
                    <div class="flex justify-between font-black text-lg text-[#800020]">
                        <span>{{ __('الإجمالي الكلي:') }}</span>
                        <span id="cart-total" dir="ltr">0.00 {{ __('ج.م') }}</span>
                    </div>
                </div>

                <button type="submit" id="submit-btn" class="w-full mt-4 bg-[#800020] text-white py-3.5 rounded-xl font-black text-lg hover:bg-[#600018] shadow-md transition">
                    {{ __('تأكيد الحجز وإصدار الفاتورة') }}
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    const trans = {
        booking: "{{ __('حجز:') }}",
        time: "{{ __('الساعة') }}",
        directPurchase: "{{ __('شراء مباشر') }}",
        egp: "{{ __('ج.م') }}",
        processing: "{{ __('جاري التنفيذ...') }}",
        confirmBtn: "{{ __('تأكيد الحجز وإصدار الفاتورة') }}",
        errorOrder: "{{ __('تعذر إتمام الطلب: ') }}",
        errorServer: "{{ __('حدث خطأ في الاتصال بالخادم.') }}"
    };

    function renderCart() {
        const cart = JSON.parse(localStorage.getItem('pharmacy_cart')) || [];
        const container = document.getElementById('cart-container');
        const emptyMsg = document.getElementById('cart-empty-msg');
        const tbody = document.getElementById('cart-items-body');

        if (cart.length === 0) {
            container.classList.add('hidden');
            emptyMsg.classList.remove('hidden');
            return;
        }

        container.classList.remove('hidden');
        emptyMsg.classList.add('hidden');
        tbody.innerHTML = '';
        let grandTotal = 0;

        cart.forEach((item, index) => {
            grandTotal += item.subtotal;
            const details = item.type === 'rental' 
                ? `<span class="text-xs text-blue-600 block mt-1" dir="auto">${trans.booking} <span dir="ltr">${item.booking_date}</span> (${trans.time} <span dir="ltr">${item.start_time}</span>)</span>` 
                : `<span class="text-xs text-green-600 block mt-1">${trans.directPurchase}</span>`;

            tbody.innerHTML += `
                <tr class="hover:bg-gray-50 transition">
                    <td class="p-3 font-bold text-gray-800">${item.item_name}</td>
                    <td class="p-3 text-sm text-gray-600">${item.quantity} ${item.unit_type} ${details}</td>
                    <td class="p-3 font-black text-[#800020]" dir="ltr">${item.subtotal.toFixed(2)} ${trans.egp}</td>
                    <td class="p-3 text-center">
                        <button onclick="removeFromCart(${index})" class="text-red-500 hover:bg-red-50 p-2 rounded-lg transition" title="حذف">
                            <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </td>
                </tr>
            `;
        });
        document.getElementById('cart-total').textContent = grandTotal.toFixed(2) + ' ' + trans.egp;
    }

    function removeFromCart(index) {
        let cart = JSON.parse(localStorage.getItem('pharmacy_cart')) || [];
        cart.splice(index, 1);
        localStorage.setItem('pharmacy_cart', JSON.stringify(cart));
        renderCart();
    }

    async function handleCheckout(e) {
        e.preventDefault();
        const cart = JSON.parse(localStorage.getItem('pharmacy_cart')) || [];
        if(cart.length === 0) return;

        const btn = document.getElementById('submit-btn');
        btn.disabled = true;
        btn.innerHTML = trans.processing;

        const payload = {
            customer_name: document.getElementById('customer_name').value,
            customer_national_id: document.getElementById('customer_national_id').value,
            customer_phone: document.getElementById('customer_phone').value,
            customer_email: document.getElementById('customer_email').value,
            cart_items: cart,
            _token: "{{ csrf_token() }}"
        };

        try {
            const response = await fetch("{{ route('checkout.process') }}", {
                method: "POST",
                headers: { "Content-Type": "application/json", "Accept": "application/json" },
                body: JSON.stringify(payload)
            });

            const result = await response.json();

            if (result.success) {
                localStorage.removeItem('pharmacy_cart');
                window.location.href = result.redirect_url;
            } else {
                alert(trans.errorOrder + result.message);
                btn.disabled = false;
                btn.innerHTML = trans.confirmBtn;
            }
        } catch (err) {
            alert(trans.errorServer);
            btn.disabled = false;
            btn.innerHTML = trans.confirmBtn;
        }
    }

    document.addEventListener('DOMContentLoaded', renderCart);
</script>
@endsection