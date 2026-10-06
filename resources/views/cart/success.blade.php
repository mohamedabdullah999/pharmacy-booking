@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto my-12 px-4">
    <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden text-center p-8 md:p-12">
        
        <div class="w-20 h-20 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>

        <h1 class="text-3xl font-black text-gray-900 mb-2">{{ __('تم تسجيل طلبك بنجاح!') }}</h1>
        <p class="text-gray-500 text-sm font-semibold mb-8">{{ __('يرجى الاحتفاظ بالرقم المرجعي لمتابعة إجراءات الدفع والتأكيد.') }}</p>

        <div class="bg-slate-50 border border-dashed border-slate-300 p-6 rounded-2xl inline-block mb-10 min-w-[300px]">
            <span class="text-xs text-slate-400 font-bold block mb-1">{{ __('الرقم المرجعي العام للطلب') }}</span>
            <span class="text-3xl font-black text-[var(--color-zu-maroon)] tracking-widest" dir="ltr">{{ $order->reference_number }}</span>
        </div>

        <!-- تفاصيل الأجهزة والحجوزات -->
        <div class="text-start mb-8">
            <h3 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100">{{ __('تفاصيل العناصر المحجوزة') }}</h3>
            <div class="overflow-x-auto border border-gray-200 rounded-2xl">
                <table class="w-full text-start text-sm">
                    <thead class="bg-slate-100 text-slate-700">
                        <tr>
                            <th class="p-4 text-start">{{ __('العنصر') }}</th>
                            <th class="p-4 text-start">{{ __('الفترة المحددة') }}</th>
                            <th class="p-4 text-start">{{ __('الكمية/المدة') }}</th>
                            <th class="p-4 text-end">{{ __('السعر الإجمالي') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($order->items as $booking)
                        <tr class="hover:bg-slate-50">
                            <td class="p-4 font-bold text-gray-900 text-start">
                                {{ $booking->item->name ?? __('عنصر غير محدد') }}
                            </td>
                            <td class="p-4 text-gray-600 text-start">
                                @if($booking->booking_date)
                                    <div class="text-xs text-slate-500 font-mono" dir="ltr">
                                        {{ $booking->booking_date }} ({{ $booking->start_time }}) <br>
                                        {{ __('إلى') }} {{ $booking->end_date ?? $booking->booking_date }} ({{ $booking->end_time }})
                                    </div>
                                @else
                                    <span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded-md font-bold">{{ __('مستلزمات (شراء)') }}</span>
                                @endif
                            </td>
                            <td class="p-4 text-gray-700 font-semibold text-start">
                                {{ (float)$booking->requested_amount }} {{ $booking->pricingRule->unit_type ?? '' }}
                            </td>
                            <td class="p-4 text-end font-black text-[var(--color-zu-maroon)]" dir="ltr">
                                {{ number_format($booking->total_price, 2) }} {{ __('ج.م') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-4 justify-center mt-8">
            <a href="{{ route('order.pdf', $order->id) }}" class="bg-[var(--color-zu-maroon)] text-white px-8 py-3.5 rounded-xl font-bold hover:bg-red-900 transition flex items-center justify-center gap-2 shadow-lg shadow-red-900/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                {{ __('تحميل الفاتورة PDF') }}
            </a>
            <a href="{{ route('catalog.index') }}" class="bg-slate-100 text-slate-700 px-8 py-3.5 rounded-xl font-bold hover:bg-slate-200 transition">
                {{ __('العودة للرئيسية') }}
            </a>
        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        localStorage.removeItem('pharmacy_cart');
    });
</script>
@endsection