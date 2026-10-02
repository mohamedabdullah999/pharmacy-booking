@extends('layouts.app')

@section('content')
    <div class="mb-8 text-center">
        <h2 class="text-3xl font-bold text-[var(--color-zu-blue)] mb-2">الخدمات المعملية والأجهزة</h2>
        <p class="text-gray-600">تصفح الأجهزة المتاحة للإيجار والمستلزمات المعملية للبيع</p>
    </div>

    <div class="flex flex-col md:flex-row gap-8">
        
        <aside class="w-full md:w-1/4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 sticky top-6">
                <h3 class="font-black text-lg text-[var(--color-zu-maroon)] mb-4 border-b pb-3">تصفية بالأقسام</h3>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('catalog.index') }}" 
                           class="block px-4 py-3 rounded-lg transition-all duration-200 {{ !request('department_id') ? 'bg-[var(--color-zu-blue)] text-white font-bold shadow-md' : 'text-gray-700 hover:bg-gray-100 font-semibold' }}">
                            جميع الأقسام
                        </a>
                    </li>
                    @foreach($departments as $dept)
                        <li>
                            <a href="{{ route('catalog.index', ['department_id' => $dept->id]) }}" 
                               class="block px-4 py-3 rounded-lg transition-all duration-200 {{ request('department_id') == $dept->id ? 'bg-[var(--color-zu-blue)] text-white font-bold shadow-md' : 'text-gray-700 hover:bg-gray-100 font-semibold' }}">
                                {{ $dept->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </aside>

        <main class="w-full md:w-3/4">
            
            <form action="{{ route('catalog.index') }}" method="GET" class="mb-8 flex gap-3 bg-white p-2 rounded-xl shadow-sm border border-gray-200">
                @if(request('department_id'))
                    <input type="hidden" name="department_id" value="{{ request('department_id') }}">
                @endif
                
                <input type="text" name="search" value="{{ request('search') }}" placeholder="ابحث عن جهاز أو منتج..." 
                       class="w-full border-none bg-transparent px-4 py-3 focus:ring-0 text-gray-700 font-medium placeholder-gray-400">
                
                <button type="submit" class="bg-[var(--color-zu-maroon)] text-white px-8 py-3 rounded-lg hover:bg-opacity-90 transition font-bold shadow-sm flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    بحث
                </button>
            </form>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
               @forelse($items as $item)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow duration-300 flex flex-col group">
                        
                        <div class="px-4 py-2 text-sm font-bold text-white text-center {{ $item->type === 'sale' ? 'bg-[var(--color-zu-green)]' : 'bg-[var(--color-zu-blue)]' }}">
                            {{ $item->type === 'sale' ? 'مستلزمات للبيع' : 'جهاز للإيجار' }}
                        </div>

                        @if($item->type === 'rental')
                        <div class="h-48 w-full bg-gray-50 border-b border-gray-100 flex items-center justify-center overflow-hidden relative p-4">
                            @if($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" loading="lazy" class="w-full h-full object-contain transition-transform duration-500 group-hover:scale-110">
                            @else
                                <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            @endif
                        </div>
                        @endif

                        <div class="p-5 flex-grow">
                            <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $item->name }}</h3>
                            <p class="text-xs font-semibold text-[var(--color-zu-maroon)] mb-4 bg-red-50 inline-block px-2 py-1 rounded">{{ $item->department->name }}</p>
                            
                            @if($item->brand || $item->model)
                                <div class="text-sm text-gray-600 mb-4 bg-gray-50 p-3 rounded-lg border border-gray-100 space-y-1">
                                    @if($item->brand) <div><strong class="text-gray-800">الماركة:</strong> {{ $item->brand }}</div> @endif
                                    @if($item->model) <div><strong class="text-gray-800">الموديل:</strong> {{ $item->model }}</div> @endif
                                </div>
                            @endif

                            <div class="space-y-2 mb-4">
                                @foreach($item->pricingRules as $rule)
                                    <div class="flex flex-col text-sm bg-blue-50/50 p-3 rounded-lg border border-blue-100/50">
                                        <div class="flex justify-between items-center font-bold text-[var(--color-zu-blue)]">
                                            <span>{{ $rule->price }} ج.م</span>
                                            <span>/ {{ $rule->unit_type }}</span>
                                        </div>
                                        @if($rule->condition_text || $rule->min_duration)
                                            <div class="mt-2 flex flex-wrap gap-1">
                                                @if($rule->condition_text)
                                                    <span class="text-xs bg-white text-gray-500 px-2 py-1 rounded border shadow-sm">{{ $rule->condition_text }}</span>
                                                @endif
                                                @if($rule->min_duration)
                                                    <span class="text-xs bg-gray-200 text-gray-700 px-2 py-1 rounded">حد أدنى: {{ $rule->min_duration }}</span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="p-4 border-t border-gray-100 mt-auto bg-gray-50/50">
                            <a href="{{ route('catalog.show', $item->id) }}" class="block text-center w-full bg-[var(--color-zu-blue)] text-white py-3 rounded-lg font-bold hover:bg-[var(--color-zu-maroon)] transition duration-300 shadow-md hover:shadow-lg">
                                {{ $item->type === 'sale' ? 'شراء الآن' : 'حجز الجهاز' }}
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white rounded-xl shadow-sm border border-gray-200 text-center py-16">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        <h3 class="text-lg font-bold text-gray-700 mb-2">لا توجد نتائج</h3>
                        <p class="text-gray-500">لم نعثر على أجهزة أو منتجات تطابق بحثك في هذا القسم.</p>
                    </div>
                @endforelse
            </div>
        </main>
    </div>
@endsection