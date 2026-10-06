@extends('layouts.admin')

@section('title', __('إدارة الفواتير والطلبات'))

@section('content')
<div class="container mx-auto px-4 py-8 text-start">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">{{ __('إدارة الفواتير والطلبات') }}</h1>
    </div>

    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 mb-6">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="flex gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('بحث بالرقم المرجعي أو القومي...') }}" class="flex-1 p-2 border border-gray-300 rounded-lg">
            <select name="status" class="p-2 border border-gray-300 rounded-lg bg-white">
                <option value="">{{ __('كل الحالات') }}</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('قيد الانتظار') }}</option>
                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>{{ __('مدفوعة') }}</option>
                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>{{ __('منتهية/ملغية') }}</option>
            </select>
            <button type="submit" class="bg-[#00315f] text-white px-6 py-2 rounded-lg font-bold">{{ __('بحث') }}</button>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-start text-sm">
                <thead class="bg-gray-50 border-b text-gray-600">
                    <tr>
                        <th class="p-4 text-start">{{ __('المرجع') }}</th>
                        <th class="p-4 text-start">{{ __('العميل') }}</th>
                        <th class="p-4 text-start">{{ __('الإجمالي') }}</th>
                        <th class="p-4 text-start">{{ __('الحالة') }}</th>
                        <th class="p-4 text-start">{{ __('تاريخ الطلب') }}</th>
                        <th class="p-4 text-center">{{ __('إجراءات') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="p-4 font-bold text-[var(--color-zu-maroon)] text-start" dir="ltr">{{ $order->reference_number }}</td>
                        <td class="p-4 text-start">
                            {{ $order->customer_name }}
                            <span class="block text-xs text-gray-500" dir="ltr">{{ $order->customer_phone }}</span>
                        </td>
                        <td class="p-4 font-bold text-[#800020] text-start" dir="ltr">{{ number_format($order->total_amount ?? $order->total_price ?? 0, 2) }} {{ __('ج.م') }}</td>
                        <td class="p-4 text-start">
                            @if($order->status == 'pending')
                                <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-bold">{{ __('قيد الانتظار') }}</span>
                            @elseif($order->status == 'paid')
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-bold">{{ __('مدفوع') }}</span>
                            @else
                                <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-bold">{{ __('ملغي') }}</span>
                            @endif
                        </td>
                        <td class="p-4 text-gray-500 text-xs text-start" dir="ltr">{{ $order->created_at ? \Carbon\Carbon::parse($order->created_at)->format('Y-m-d H:i') : '' }}</td>
                        <td class="p-4 text-center">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="text-[#00315f] hover:underline font-bold text-sm">{{ __('عرض التفاصيل') }}</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-gray-500 font-bold">{{ __('لا توجد طلبات مطابقة للبحث.') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-4 border-t border-gray-200">
            {{ $orders->appends(request()->query())->links('pagination::tailwind') }}
        </div>
    </div>
</div>
@endsection