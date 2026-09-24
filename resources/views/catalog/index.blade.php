@extends('layouts.app')

@section('content')
    <div class="mb-8 text-center">
        <h2 class="text-3xl font-bold text-[var(--color-zu-blue)] mb-2">الخدمات المعملية والأجهزة</h2>
        <p class="text-gray-600">تصفح الأجهزة المتاحة للإيجار والمستلزمات المعملية للبيع</p>
    </div>

    <div class="mb-8 flex justify-center">
        <form action="{{ route('catalog.index') }}" method="GET" class="flex gap-2">
            <select name="department_id" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-[var(--color-zu-blue)] focus:border-[var(--color-zu-blue)]">
                <option value="">جميع الأقسام</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                        {{ $dept->name }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="bg-[var(--color-zu-maroon)] text-white px-6 py-2 rounded-lg hover:bg-opacity-90 transition">
                تصفية
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
       @forelse($items as $item)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition flex flex-col">
                
                <div class="px-4 py-2 text-sm font-bold text-white text-center {{ $item->type === 'sale' ? 'bg-[var(--color-zu-green)]' : 'bg-[var(--color-zu-blue)]' }}">
                    {{ $item->type === 'sale' ? 'مستلزمات للبيع' : 'جهاز للإيجار' }}
                </div>

                @if($item->type === 'rental')
                <div class="h-48 w-full bg-gray-100 border-b border-gray-100 flex items-center justify-center overflow-hidden relative">
                    @if($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" loading="lazy" class="w-full h-full object-cover transition-transform duration-500 hover:scale-110">
                    @else
                        <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    @endif
                </div>
                @endif

                <div class="p-5 flex-grow">
                    <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $item->name }}</h3>
                    <p class="text-sm text-[var(--color-zu-maroon)] mb-3">{{ $item->department->name }}</p>
                    
                    @if($item->brand || $item->model)
                        <div class="text-sm text-gray-600 mb-4 bg-gray-50 p-2 rounded">
                            @if($item->brand) <div><strong>الماركة:</strong> {{ $item->brand }}</div> @endif
                            @if($item->model) <div><strong>الموديل:</strong> {{ $item->model }}</div> @endif
                        </div>
                    @endif

                    <div class="space-y-2 mb-4">
                        <h4 class="text-sm font-bold text-gray-700 border-b pb-1">نظام التسعير:</h4>
                        @foreach($item->pricingRules as $rule)
                            <div class="flex justify-between items-center text-sm bg-blue-50/50 p-2 rounded">
                                <span class="text-gray-700">
                                    {{ $rule->price }} جنيه / {{ $rule->unit_type }}
                                    @if($rule->condition_text)
                                        <span class="block text-xs text-gray-500">{{ $rule->condition_text }}</span>
                                    @endif
                                </span>
                                @if($rule->min_duration)
                                    <span class="text-xs bg-gray-200 px-2 py-1 rounded text-gray-600">حد أدنى: {{ $rule->min_duration }}</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="p-4 border-t bg-gray-50 mt-auto">
                    <button class="w-full bg-[var(--color-zu-blue)] text-white py-2 rounded font-bold hover:bg-[var(--color-zu-maroon)] transition duration-300 shadow-sm hover:shadow-md">
                        {{ $item->type === 'sale' ? 'شراء الآن' : 'حجز الجهاز' }}
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12 text-gray-500">
                لا توجد منتجات أو أجهزة متاحة في هذا القسم حالياً.
            </div>
        @endforelse
    </div>
@endsection