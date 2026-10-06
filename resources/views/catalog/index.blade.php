@extends('layouts.app')

@section('content')
    <div class="mb-10 text-center">
        <h2 class="text-3xl font-black text-[var(--color-zu-blue)] mb-3">{{ __('الخدمات المعملية والأجهزة') }}</h2>
        <p class="text-gray-500 font-medium">{{ __('تصفح الأجهزة المتاحة للإيجار والمستلزمات المعملية للبيع') }}</p>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">
        
        <aside class="w-full lg:w-1/4">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-6">
                <div class="flex items-center gap-2 mb-4 pb-4 border-b-2 border-[var(--color-zu-maroon)]">
                    <svg class="w-6 h-6 text-[var(--color-zu-maroon)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                    <h3 class="font-bold text-lg text-gray-800">{{ __('تصنيف بالأقسام') }}</h3>
                </div>
                
                <ul class="space-y-1.5">
                    <li>
                        <a href="{{ route('catalog.index') }}" class="flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-200 {{ !request('department_id') ? 'bg-[var(--color-zu-blue)] text-white shadow-md' : 'text-gray-600 hover:bg-gray-50' }}">
                           <span class="font-semibold flex items-center gap-3">
                                {{ __('جميع الأقسام') }}
                           </span>
                        </a>
                    </li>
                    @foreach($departments as $dept)
                        <li>
                            <a href="{{ route('catalog.index', ['department_id' => $dept->id]) }}" class="flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-200 {{ request('department_id') == $dept->id ? 'bg-[var(--color-zu-blue)] text-white shadow-md' : 'text-gray-600 hover:bg-gray-50 border border-transparent hover:border-gray-100' }}">
                                <span class="font-semibold flex items-center gap-3">
                                    {{ __($dept->name) }}
                                </span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </aside>

        <main class="w-full lg:w-3/4">
            <form action="{{ route('catalog.index') }}" method="GET" class="mb-8 bg-white p-2 rounded-2xl shadow-sm border border-gray-100 flex items-center">
                @if(request('department_id'))
                    <input type="hidden" name="department_id" value="{{ request('department_id') }}">
                @endif
                
                <div class="flex-grow flex items-center px-4">
                    <svg class="w-5 h-5 text-gray-400 ms-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('ابحث عن جهاز أو اسم...') }}" 
                           class="w-full border-none bg-transparent py-2 focus:ring-0 text-gray-700 font-medium outline-none">
                </div>

                <button type="submit" class="bg-[var(--color-zu-maroon)] text-white px-8 py-3 rounded-xl hover:bg-red-800 transition font-bold shadow-md flex items-center gap-2 shrink-0">
                    {{ __('بحث') }}
                </button>
            </form>

            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
               @forelse($items as $item)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300 flex flex-col group">
                        
                        <div class="px-4 py-3 text-sm font-bold text-white text-center {{ $item->type === 'sale' ? 'bg-[var(--color-zu-green)]' : 'bg-[var(--color-zu-blue)]' }}">
                            {{ $item->type === 'sale' ? __('مستلزمات للبيع') : __('جهاز للإيجار') }}
                        </div>

                        <div class="h-48 w-full bg-gray-50 flex items-center justify-center overflow-hidden relative p-4 border-b border-gray-100">
                            @if($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="w-full h-full object-contain transition-transform duration-500 group-hover:scale-110">
                            @else
                                <svg class="w-16 h-16 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            @endif
                        </div>

                        <div class="p-5 flex-grow flex flex-col justify-between text-center">
                            <div>
                                <h3 class="text-lg font-black text-gray-900 mb-1">{{ $item->name }}</h3>
                                <!-- هنا تم وضع دالة الترجمة للقسم الخاص بالجهاز -->
                                <p class="text-sm font-semibold text-[var(--color-zu-maroon)] mb-4">{{ __($item->department->name) }}</p>
                            </div>

                            <a href="{{ route('catalog.show', $item->id) }}" class="mt-4 block w-full bg-white text-[var(--color-zu-maroon)] border border-[var(--color-zu-maroon)] py-2.5 rounded-xl font-bold hover:bg-[var(--color-zu-maroon)] hover:text-white transition-colors duration-300">
                                {{ __('عرض التفاصيل') }}
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white rounded-2xl shadow-sm border border-gray-100 text-center py-16">
                        <p class="text-gray-500">{{ __('لا توجد نتائج') }}</p>
                    </div>
                @endforelse
            </div>
            
        </main>
    </div>
@endsection