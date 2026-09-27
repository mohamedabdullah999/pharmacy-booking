@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-sm border border-t-8 border-t-[var(--color-zu-blue)] p-8 text-center mt-10">
    
    <div class="w-20 h-20 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-6">
        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
    </div>
    
    <h2 class="text-3xl font-black text-gray-900 mb-2">تم تسجيل طلبك بنجاح!</h2>
    <p class="text-gray-600 mb-8 font-semibold">يرجى التوجه إلى خزينة الكلية لدفع الرسوم المطلوبة لتأكيد حجزك بشكل نهائي.</p>
    
    <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 mb-8 text-right shadow-inner">
        <h3 class="text-lg font-bold text-[var(--color-zu-blue)] mb-4 border-b pb-2">تفاصيل الطلب</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-8 text-sm">
            <div class="flex flex-col">
                <span class="text-gray-500 mb-1">الرقم المرجعي (هام جداً للخزينة):</span>
                <span class="font-black text-xl text-[var(--color-zu-maroon)] text-left bg-white p-2 rounded border" dir="ltr">{{ $booking->reference_number }}</span>
            </div>
            
            <div class="flex flex-col">
                <span class="text-gray-500 mb-1">الجهاز / المنتج:</span>
                <span class="font-bold text-gray-900 text-lg">{{ $booking->item->name }}</span>
            </div>

            <div class="flex flex-col">
                <span class="text-gray-500 mb-1">الكمية / المدة المطلوبة:</span>
                <span class="font-bold text-gray-900">{{ (float)$booking->requested_amount }} {{ $booking->pricingRule->unit_type }}</span>
            </div>
            
            <div class="flex flex-col">
                <span class="text-gray-500 mb-1">المبلغ الإجمالي المطلوب:</span>
                <span class="font-black text-[var(--color-zu-blue)] text-xl">{{ $booking->total_price }} جنيه</span>
            </div>

            @if($booking->item->type === 'rental' && $booking->booking_date)
            <div class="col-span-1 md:col-span-2 mt-2 pt-4 border-t border-gray-200">
                <h4 class="font-bold text-gray-700 mb-3">مواعيد تشغيل الجهاز:</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-blue-50 p-3 rounded-lg border border-blue-100">
                    <div>
                        <span class="block text-xs text-gray-500">تاريخ الحجز:</span>
                        <span class="font-bold text-gray-900" dir="ltr">{{ \Carbon\Carbon::parse($booking->booking_date)->format('Y-m-d') }}</span>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-500">وقت البدء:</span>
                        <span class="font-bold text-green-700" dir="ltr">{{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }}</span>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-500">وقت الانتهاء المتوقع:</span>
                        <span class="font-bold text-red-600" dir="ltr">{{ $booking->end_time ? \Carbon\Carbon::parse($booking->end_time)->format('h:i A') : 'غير محدد' }}</span>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    
    <div class="bg-red-50 border-r-4 border-red-500 text-red-800 p-4 rounded-lg text-sm mb-6 text-right shadow-sm">
        <div class="flex items-center gap-2 mb-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <strong class="text-lg">تنبيه هام (مهلة سداد الرسوم)</strong>
        </div>
        <p class="mb-2">هذا الحجز يعتبر <strong>مبدئياً</strong> ولن يتم تأكيده إلا بعد سداد الرسوم في الخزينة.</p>
        <p>المهلة المتاحة لك للسداد تنتهي في: <strong class="text-xl inline-block mt-1" dir="ltr">{{ $booking->expires_at->format('Y-m-d h:i A') }}</strong></p>
        <p class="text-xs mt-2 opacity-80">إذا لم يتم السداد قبل هذا الموعد، سيقوم النظام بإلغاء الطلب تلقائياً.</p>
    </div>

    @if(session('warning'))
        <div class="mb-6 p-4 bg-yellow-50 border-r-4 border-yellow-500 text-yellow-800 rounded-lg text-sm text-right font-bold shadow-sm animate-pulse">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                {{ session('warning') }}
            </div>
        </div>
    @endif

    <div class="flex flex-col sm:flex-row justify-center gap-4 mt-8">
        <a href="{{ route('booking.receipt.download', $booking->reference_number) }}" class="flex items-center justify-center gap-2 bg-green-600 text-white px-8 py-3 rounded-lg font-bold hover:bg-green-700 shadow-md transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            تحميل إيصال الحجز المبدئي (للتوجه للخزينة)
        </a>
        
        <a href="/" class="flex items-center justify-center gap-2 bg-[var(--color-zu-blue)] text-white px-8 py-3 rounded-lg font-bold hover:bg-[#002244] shadow-md transition">
            العودة للرئيسية
        </a>
    </div>
</div>
@endsection