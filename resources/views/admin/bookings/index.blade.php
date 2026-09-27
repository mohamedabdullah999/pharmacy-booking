@extends('layouts.admin')

@section('title', 'إدارة الحجوزات والمدفوعات')

@section('content')
<div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <form action="{{ route('admin.bookings.index') }}" method="GET" class="w-full md:w-2/3 flex gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="ابحث بالرقم المرجعي أو الرقم القومي..." 
               class="flex-1 p-2 border border-gray-300 rounded-lg focus:ring-[var(--color-zu-blue)]">
        
        <select name="status" class="p-2 border border-gray-300 rounded-lg focus:ring-[var(--color-zu-blue)] bg-white">
            <option value="">كل الحالات</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>في انتظار الدفع</option>
            <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>تم الدفع (مؤكد)</option>
            <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>منتهي الصلاحية</option>
        </select>
        
        <button type="submit" class="bg-[var(--color-zu-blue)] text-white px-4 py-2 rounded-lg font-bold hover:bg-[#002244] transition">تصفية</button>
    </form>
</div>

@if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border-r-4 border-green-500 text-green-800 rounded-lg font-bold shadow-sm">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="mb-6 p-4 bg-red-50 border-r-4 border-red-500 text-red-700 rounded-lg font-bold shadow-sm">
        {{ $errors->first() }}
    </div>
@endif

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-right text-sm">
            <thead class="bg-gray-50 text-gray-600 border-b">
                <tr>
                    <th class="p-4 font-bold">الرقم المرجعي</th>
                    <th class="p-4 font-bold">الطالب</th>
                    <th class="p-4 font-bold">العنصر</th>
                    <th class="p-4 font-bold">المدة/الكمية</th>
                    <th class="p-4 font-bold">المبلغ المطلوب</th>
                    <th class="p-4 font-bold">الحالة</th>
                    <th class="p-4 font-bold text-center">إجراءات الدفع</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($bookings as $booking)
                <tr class="hover:bg-gray-50 transition">
                    <td class="p-4 font-black text-[var(--color-zu-maroon)]" dir="ltr">{{ $booking->reference_number }}</td>
                    <td class="p-4">
                        <div class="font-bold text-gray-900">{{ $booking->customer_name }}</div>
                        <div class="text-xs text-gray-500">{{ $booking->customer_phone }}</div>
                    </td>
                    <td class="p-4 text-gray-700 font-semibold">{{ $booking->item->name }}</td>
                    <td class="p-4 text-gray-600" dir="ltr">
                        {{ (float)$booking->requested_amount }} {{ $booking->pricingRule->unit_type ?? '' }}
                    </td>
                    <td class="p-4 font-black text-[var(--color-zu-blue)]">{{ $booking->total_price }} ج.م</td>
                    <td class="p-4">
                        @if($booking->status === 'pending')
                            <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-bold">في انتظار الدفع</span>
                        @elseif($booking->status === 'paid')
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-bold">تم الدفع</span>
                        @elseif($booking->status === 'expired')
                            <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-bold">ملغي (مهلة منتهية)</span>
                        @endif
                        
                        @if($booking->status === 'pending')
                            <div class="text-[10px] text-gray-400 mt-1">المهلة: {{ $booking->expires_at->format('Y-m-d H:i') }}</div>
                        @endif
                    </td>
                    <td class="p-4 text-center">
                        @if($booking->status === 'pending')
                            <form action="{{ route('admin.bookings.confirm', $booking->id) }}" method="POST" onsubmit="return confirm('هل استلمت المبلغ الفعلي من الطالب في الخزينة؟ لا يمكن التراجع عن هذه الخطوة.');">
                                @csrf
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded-lg text-xs font-bold transition shadow-sm">
                                    تأكيد سداد الرسوم
                                </button>
                            </form>
                        @else
                            <span class="text-gray-400 text-xs">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="p-8 text-center text-gray-500 font-bold">لا توجد حجوزات تطابق بحثك.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="p-4 border-t border-gray-200">
        {{ $bookings->appends(request()->query())->links('pagination::tailwind') }}
    </div>
</div>
@endsection