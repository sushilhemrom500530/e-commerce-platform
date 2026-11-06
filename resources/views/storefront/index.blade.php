@extends('layouts.app')

@section('content')
<div class="space-y-12">
    <!-- Hero Banner -->
    <div class="rounded-3xl bg-gradient-to-r from-indigo-900 via-indigo-800 to-slate-900 text-white p-8 sm:p-12 shadow-xl relative overflow-hidden">
        <div class="relative z-10 max-w-2xl space-y-4">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-indigo-500/20 text-indigo-300 border border-indigo-400/30">
                ⚡ Full Catalog by Subcategory
            </span>
            <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight leading-tight">
                Discover Quality Products at Unbeatable Prices.
            </h1>
            <p class="text-slate-300 text-sm sm:text-base">
                Browse our curated categories, explore top brands, and enjoy seamless shopping with verified product details and real-time inventory.
            </p>
        </div>
        <div class="absolute -right-12 -bottom-12 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
    </div>

    <!-- Products Organized by Subcategories -->
    @forelse($subcategories as $subcategory)
        <section class="space-y-6">
            <div class="flex items-end justify-between border-b border-slate-200 pb-3">
                <div>
                    <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-indigo-600">
                        <span>{{ $subcategory->category->name }}</span>
                        <span>&bull;</span>
                        <span>Subcategory</span>
                    </div>
                    <h2 class="text-2xl font-black text-slate-900 tracking-tight mt-1">{{ $subcategory->name }}</h2>
                    @if($subcategory->description)
                        <p class="text-xs text-slate-500 mt-0.5">{{ $subcategory->description }}</p>
                    @endif
                </div>
                <span class="text-xs font-semibold text-slate-400 bg-slate-100 px-2.5 py-1 rounded-full">
                    {{ $subcategory->products->count() }} {{ Str::plural('Product', $subcategory->products->count()) }}
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($subcategory->products as $product)
                    <div class="group bg-white rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition flex flex-col overflow-hidden">
                        <!-- Image & Discount Badge -->
                        <div class="relative aspect-[4/3] bg-slate-100 overflow-hidden">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            @if($product->discount_percentage)
                                <span class="absolute top-3 left-3 bg-rose-600 text-white text-[11px] font-bold px-2 py-0.5 rounded-full shadow-sm">
                                    -{{ $product->discount_percentage }}%
                                </span>
                            @endif
                            <span class="absolute top-3 right-3 bg-white/90 backdrop-blur text-slate-700 text-[10px] font-semibold px-2 py-0.5 rounded-md shadow-xs">
                                {{ $subcategory->name }}
                            </span>
                        </div>

                        <!-- Card Body -->
                        <div class="p-4 flex-1 flex flex-col justify-between space-y-3">
                            <div>
                                <span class="text-[11px] font-semibold text-indigo-600 uppercase tracking-wider block">
                                    {{ $subcategory->category->name }}
                                </span>
                                <h3 class="font-bold text-slate-900 text-base line-clamp-1 group-hover:text-indigo-600 transition mt-0.5">
                                    <a href="{{ route('storefront.product', $product->slug) }}">
                                        {{ $product->name }}
                                    </a>
                                </h3>
                                <p class="text-xs text-slate-500 line-clamp-2 mt-1">
                                    {{ $product->description }}
                                </p>
                            </div>

                            <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                                <div>
                                    <div class="text-lg font-black text-slate-900">
                                        ৳{{ number_format($product->new_price, 2) }}
                                    </div>
                                    @if($product->old_price && $product->old_price > $product->new_price)
                                        <div class="text-xs text-slate-400 line-through">
                                            ৳{{ number_format($product->old_price, 2) }}
                                        </div>
                                    @endif
                                </div>
                                <a href="{{ route('storefront.product', $product->slug) }}" class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg bg-slate-900 text-white text-xs font-bold hover:bg-indigo-600 transition shadow-xs">
                                    Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @empty
        <div class="text-center py-20 bg-white rounded-3xl border border-dashed border-slate-300 p-8">
            <div class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-800">No products available yet</h3>
            <p class="text-sm text-slate-500 mt-1 max-w-sm mx-auto">Get started by creating categories, subcategories, and adding your first products in the admin panel.</p>
            <div class="mt-6 flex justify-center gap-3">
                <a href="{{ route('admin.categories.create') }}" class="px-4 py-2 bg-indigo-600 text-white text-xs font-bold rounded-xl shadow-sm hover:bg-indigo-700">Add Category</a>
                <a href="{{ route('admin.products.create') }}" class="px-4 py-2 bg-slate-900 text-white text-xs font-bold rounded-xl shadow-sm hover:bg-slate-800">Add Product</a>
            </div>
        </div>
    @endforelse
</div>
@endsection
