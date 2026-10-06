@extends('layouts.admin')

@section('title', 'نظرة عامة')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 border-s-4 border-s-[var(--color-zu-blue)]">
        <h3 class="text-gray-500 text-xs font-bold mb-1">{{ __('الأجهزة والمنتجات') }}</h3>
        <p class="text-2xl font-black text-gray-900">{{ $stats['total_items'] }}</p>
    </div>
    
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 border-r-4 border-r-purple-500">
        <h3 class="text-gray-500 text-xs font-bold mb-1">إجمالي الطلبات</h3>
        <p class="text-2xl font-black text-gray-900">{{ $stats['total_bookings'] }}</p>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 border-r-4 border-r-yellow-500">
        <h3 class="text-gray-500 text-xs font-bold mb-1">تنتظر الدفع</h3>
        <p class="text-2xl font-black text-yellow-600">{{ $stats['pending_bookings'] }}</p>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 border-r-4 border-r-green-500">
        <h3 class="text-gray-500 text-xs font-bold mb-1">تم الدفع (مؤكدة)</h3>
        <p class="text-2xl font-black text-green-600">{{ $stats['paid_bookings'] }}</p>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 border-r-4 border-r-red-500">
        <h3 class="text-gray-500 text-xs font-bold mb-1">ملغية (تخطت المهلة)</h3>
        <p class="text-2xl font-black text-red-600">{{ $stats['expired_bookings'] }}</p>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="p-6 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
        <h2 class="text-lg font-bold text-[var(--color-zu-blue)]">أحدث الحجوزات الواردة</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-right text-sm">
            <thead class="bg-gray-50 text-gray-600 border-b">
                <tr>
                    <th class="p-4 font-bold">الرقم المرجعي</th>
                    <th class="p-4 font-bold">الطالب</th>
                    <th class="p-4 font-bold">المنتج / الجهاز</th>
                    <th class="p-4 font-bold">المبلغ</th>
                    <th class="p-4 font-bold">الحالة</th>
                    <th class="p-4 font-bold">تاريخ الطلب</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($recentBookings as $booking)
                <tr class="hover:bg-gray-50 transition">
                    <td class="p-4 font-black text-[var(--color-zu-maroon)]" dir="ltr">{{ $booking->reference_number }}</td>
                    <td class="p-4 font-semibold text-gray-800">{{ $booking->customer_name }}</td>
                    <td class="p-4 text-gray-600">{{ $booking->item->name }}</td>
                    <td class="p-4 font-bold text-gray-900">{{ $booking->total_price }} ج.م</td>
                    <td class="p-4">
                        @if($booking->status === 'pending')
                            <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-bold">في انتظار الدفع</span>
                        @elseif($booking->status === 'paid')
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-bold">تم الدفع</span>
                        @elseif($booking->status === 'expired')
                            <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-bold">ملغي (تخطى المهلة)</span>
                        @endif
                    </td>
                    <td class="p-4 text-gray-500 text-xs" dir="ltr">{{ $booking->created_at->format('Y-m-d H:i') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-8 text-center text-gray-500">لا توجد حجوزات حتى الآن.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection