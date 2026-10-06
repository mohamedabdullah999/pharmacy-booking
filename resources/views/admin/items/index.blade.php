@extends('layouts.admin')

@section('title', __('إدارة الأجهزة والمنتجات'))

@section('content')

@if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border-s-4 border-green-500 text-green-800 rounded-lg font-bold shadow-sm">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="mb-6 p-4 bg-red-50 border-s-4 border-red-500 text-red-800 rounded-lg font-bold shadow-sm">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <form action="{{ route('admin.items.index') }}" method="GET" class="w-full sm:w-1/3 relative">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('ابحث باسم الجهاز أو الموديل...') }}" 
               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[var(--color-zu-blue)] focus:border-[var(--color-zu-blue)]">
    </form>

    <a href="{{ route('admin.items.create') }}" class="bg-[var(--color-zu-blue)] text-white px-6 py-2 rounded-lg font-bold hover:bg-[#002244] shadow-sm transition flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        {{ __('إضافة عنصر جديد') }}
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-start text-sm">
            <thead class="bg-gray-50 text-gray-600 border-b">
                <tr>
                    <th class="p-4 font-bold w-16 text-start">{{ __('الرقم') }}</th>
                    <th class="p-4 font-bold text-start">{{ __('الاسم') }}</th>
                    <th class="p-4 font-bold text-start">{{ __('القسم') }}</th>
                    <th class="p-4 font-bold text-start">{{ __('النوع') }}</th>
                    <th class="p-4 font-bold text-center">{{ __('المخزون') }}</th>
                    <th class="p-4 font-bold text-center">{{ __('الحالة') }}</th>
                    <th class="p-4 font-bold text-center">{{ __('الإجراءات') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($items as $item)
                <tr class="hover:bg-gray-50 transition">
                    <td class="p-4 text-gray-500 text-start" dir="ltr">{{ $item->id }}</td>
                    <td class="p-4 font-bold text-[var(--color-zu-blue)] text-start">{{ $item->name }}</td>
                    <td class="p-4 text-gray-600 text-start">{{ __($item->department->name ?? __('غير محدد')) }}</td>
                    <td class="p-4 text-start">
                        @if($item->type === 'sale')
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-bold">{{ __('مستلزمات (بيع)') }}</span>
                        @else
                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-bold">{{ __('جهاز (إيجار)') }}</span>
                        @endif
                    </td>
                    <td class="p-4 text-center font-bold {{ $item->stock_quantity > 0 ? 'text-gray-900' : 'text-red-500' }}" dir="ltr">
                        {{ $item->type === 'sale' ? $item->stock_quantity : '∞' }}
                    </td>
                    <td class="p-4 text-center">
                        @if($item->is_active)
                            <span class="text-green-600 font-bold text-xs"><span class="inline-block w-2 h-2 rounded-full bg-green-500 mx-1"></span>{{ __('مفعل') }}</span>
                        @else
                            <span class="text-red-600 font-bold text-xs"><span class="inline-block w-2 h-2 rounded-full bg-red-500 mx-1"></span>{{ __('معطل') }}</span>
                        @endif
                    </td>
                    <td class="p-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin.items.edit', $item->id) }}" class="text-blue-600 hover:text-blue-900 bg-blue-50 p-2 rounded-lg transition" title="{{ __('تعديل') }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>

                            <form action="{{ route('admin.items.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('هل أنت متأكد من حذف هذا العنصر نهائياً؟ لا يمكن التراجع عن هذه الخطوة.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 p-2 rounded-lg transition" title="{{ __('حذف') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="p-8 text-center text-gray-500 font-bold">{{ __('لا توجد أجهزة أو منتجات مسجلة حالياً.') }}</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="p-4 border-t border-gray-200">
        {{ $items->links('pagination::tailwind') }}
    </div>
</div>
@endsection