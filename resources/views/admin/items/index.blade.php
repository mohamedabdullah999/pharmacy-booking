@extends('layouts.admin')

@section('title', 'إدارة الأجهزة والمنتجات')

@section('content')

@if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border-r-4 border-green-500 text-green-800 rounded-lg font-bold shadow-sm">
        {{ session('success') }}
    </div>
@endif

<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <form action="{{ route('admin.items.index') }}" method="GET" class="w-full sm:w-1/3 relative">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="ابحث باسم الجهاز أو الموديل..." 
               class="w-full pl-4 pr-10 py-2 border border-gray-300 rounded-lg focus:ring-[var(--color-zu-blue)] focus:border-[var(--color-zu-blue)]">
        <svg class="w-5 h-5 text-gray-400 absolute right-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
    </form>

    <a href="{{ route('admin.items.create') }}" class="bg-[var(--color-zu-blue)] text-white px-6 py-2 rounded-lg font-bold hover:bg-[#002244] shadow-sm transition flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        إضافة عنصر جديد
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-right text-sm">
            <thead class="bg-gray-50 text-gray-600 border-b">
                <tr>
                    <th class="p-4 font-bold w-16">#</th>
                    <th class="p-4 font-bold">الاسم</th>
                    <th class="p-4 font-bold">القسم</th>
                    <th class="p-4 font-bold">النوع</th>
                    <th class="p-4 font-bold text-center">المخزون</th>
                    <th class="p-4 font-bold text-center">الحالة</th>
                    <th class="p-4 font-bold text-center">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($items as $item)
                <tr class="hover:bg-gray-50 transition">
                    <td class="p-4 text-gray-500">{{ $item->id }}</td>
                    <td class="p-4 font-bold text-[var(--color-zu-blue)]">{{ $item->name }}</td>
                    <td class="p-4 text-gray-600">{{ $item->department->name ?? 'غير محدد' }}</td>
                    <td class="p-4">
                        @if($item->type === 'sale')
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-bold">مستلزمات (بيع)</span>
                        @else
                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-bold">جهاز (إيجار)</span>
                        @endif
                    </td>
                    <td class="p-4 text-center font-bold {{ $item->stock_quantity > 0 ? 'text-gray-900' : 'text-red-500' }}">
                        {{ $item->type === 'sale' ? $item->stock_quantity : '∞' }}
                    </td>
                    <td class="p-4 text-center">
                        @if($item->is_active)
                            <span class="text-green-600 font-bold text-xs"><span class="inline-block w-2 h-2 rounded-full bg-green-500 mr-1"></span>مفعل</span>
                        @else
                            <span class="text-red-600 font-bold text-xs"><span class="inline-block w-2 h-2 rounded-full bg-red-500 mr-1"></span>معطل</span>
                        @endif
                    </td>
                    <td class="p-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin.items.edit', $item->id) }}" class="text-blue-600 hover:text-blue-900 bg-blue-50 p-2 rounded-lg transition" title="تعديل">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="p-8 text-center text-gray-500 font-bold">لا توجد أجهزة أو منتجات مسجلة حالياً.</td>
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