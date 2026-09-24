@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-sm border border-t-8 border-t-[var(--color-zu-blue)] p-8 text-center mt-10">
    <div class="w-20 h-20 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-6">
        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
    </div>
    
    <h2 class="text-3xl font-black text-gray-900 mb-2">تم الحجز المبدئي بنجاح!</h2>
    <p class="text-gray-600 mb-8">يرجى التوجه إلى خزينة الكلية لدفع الرسوم المطلوبة لتأكيد حجزك.</p>
    
    <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 mb-8 text-right">
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div class="text-gray-500">الرقم المرجعي (هام جداً):</div>
            <div class="font-black text-xl text-[var(--color-zu-maroon)] text-left" dir="ltr">{{ $booking->reference_number }}</div>
            
            <div class="text-gray-500">الجهاز / المنتج:</div>
            <div class="font-bold text-gray-900 text-left">{{ $booking->item->name }}</div>
            
            <div class="text-gray-500">المبلغ المطلوب:</div>
            <div class="font-bold text-[var(--color-zu-blue)] text-left">{{ $booking->total_price }} جنيه</div>
            
            <div class="text-gray-500">ينتهي الحجز في:</div>
            <div class="font-bold text-red-600 text-left" dir="ltr">{{ $booking->expires_at->format('Y-m-d H:i') }}</div>
        </div>
    </div>
    
    <div class="bg-yellow-50 text-yellow-800 p-4 rounded-lg text-sm mb-6">
        <strong>تنبيه هام:</strong> سيتم إلغاء هذا الحجز تلقائياً إذا لم يتم السداد قبل الموعد المذكور أعلاه (خلال 48 ساعة).
    </div>

    <a href="/" class="inline-block bg-gray-200 text-gray-800 px-6 py-3 rounded-lg font-bold hover:bg-gray-300 transition">العودة للرئيسية</a>
</div>
@endsection