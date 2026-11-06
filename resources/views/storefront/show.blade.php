@extends('layouts.app')

@section('content')
<div class="space-y-10">
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-2 text-xs font-semibold text-slate-500">
        <a href="{{ route('storefront.index') }}" class="hover:text-indigo-600 transition">Home</a>
        <span>/</span>
        <span class="text-slate-600">{{ $product->category->name }}</span>
        <span>/</span>
        <span class="text-slate-600">{{ $product->subcategory->name }}</span>
        <span>/</span>
        <span class="text-slate-900 font-bold truncate max-w-xs">{{ $product->name }}</span>
    </nav>

    <!-- Product Detail Container -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-10">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-start">
            <!-- Product Image & Gallery -->
            <div class="space-y-4">
                <div class="relative rounded-2xl overflow-hidden bg-[#F8F9FD] aspect-square border border-slate-200/60 shadow-xs p-4 flex items-center justify-center">
                    <img id="main-product-image" src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-contain">
                    @if($product->discount_percentage)
                        <div class="absolute top-4 left-4 bg-rose-600 text-white text-xs font-black px-3 py-1 rounded-full shadow-md">
                            SAVE {{ $product->discount_percentage }}%
                        </div>
                    @endif
                </div>

                @if(!empty($product->featured_images_urls) && count($product->featured_images_urls) > 0)
                    <!-- Featured Images Gallery Thumbnails -->
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2">Featured Photos</p>
                        <div class="flex items-center gap-2.5 overflow-x-auto pb-1">
                            <button type="button" 
                                    onclick="document.getElementById('main-product-image').src='{{ $product->image_url }}'"
                                    class="w-16 h-16 rounded-xl border-2 border-indigo-600 overflow-hidden p-1 bg-[#F8F9FD] hover:opacity-90 transition flex-shrink-0 cursor-pointer">
                                <img src="{{ $product->image_url }}" class="w-full h-full object-contain">
                            </button>
                            @foreach($product->featured_images_urls as $fImg)
                                <button type="button" 
                                        onclick="document.getElementById('main-product-image').src='{{ $fImg }}'"
                                        class="w-16 h-16 rounded-xl border border-slate-200 hover:border-indigo-600 overflow-hidden p-1 bg-[#F8F9FD] hover:opacity-90 transition flex-shrink-0 cursor-pointer">
                                    <img src="{{ $fImg }}" class="w-full h-full object-contain">
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Product Information -->
            <div class="space-y-6">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                            {{ $product->category->name }}
                        </span>
                        <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-100 text-slate-700">
                            {{ $product->subcategory->name }}
                        </span>
                        @if($product->is_active)
                            <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-700 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span> In Stock
                            </span>
                        @endif
                    </div>
                    <h1 class="text-2xl sm:text-4xl font-black text-slate-900 tracking-tight mt-3">
                        {{ $product->name }}
                    </h1>
                    <p class="text-xs text-slate-400 mt-1">SEO URL Slug: <span class="font-mono text-indigo-600">{{ $product->slug }}</span></p>
                </div>

                <!-- Price Box -->
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 flex items-center justify-between">
                    <div>
                        <div class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Current Price</div>
                        <div class="text-3xl font-black text-slate-900 mt-0.5">
                            ৳{{ number_format($product->new_price, 2) }}
                        </div>
                    </div>
                    @if($product->old_price && $product->old_price > $product->new_price)
                        <div class="text-right">
                            <div class="text-xs text-slate-400 font-medium">Original Price</div>
                            <div class="text-base font-bold text-slate-400 line-through">
                                ৳{{ number_format($product->old_price, 2) }}
                            </div>
                            <div class="text-xs font-bold text-emerald-600 mt-0.5">
                                You Save ৳{{ number_format($product->old_price - $product->new_price, 2) }}
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Description -->
                <div class="space-y-2">
                    <h2 class="text-sm font-bold uppercase tracking-wider text-slate-900">Description</h2>
                    <div class="text-sm text-slate-600 leading-relaxed whitespace-pre-line bg-slate-50/50 p-4 rounded-xl border border-slate-100">
                        {{ $product->description }}
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="pt-4 flex flex-wrap items-center gap-3">
                    <button type="button" 
                            onclick="window.addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ (float) $product->new_price }}, '{{ addslashes($product->image_url) }}', '{{ $product->slug }}')"
                            class="px-6 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold shadow-md shadow-indigo-100 transition active:scale-95 inline-flex items-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <span>Add to Cart</span>
                    </button>
                    <a href="{{ route('storefront.index') }}" class="px-5 py-3 rounded-xl border border-slate-300 text-slate-700 text-sm font-bold hover:bg-slate-100 transition inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        <span>Catalog</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if(isset($relatedProducts) && $relatedProducts->count() > 0)
        <section class="space-y-4">
            <h2 class="text-xl font-bold text-slate-900">More from {{ $product->subcategory->name }}</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedProducts as $related)
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-3 shadow-xs hover:shadow-md transition">
                        <div class="aspect-[4/3] rounded-xl overflow-hidden bg-slate-100 mb-3">
                            <img src="{{ $related->image_url }}" alt="{{ $related->name }}" class="w-full h-full object-cover">
                        </div>
                        <h3 class="font-bold text-sm text-slate-900 truncate">
                            <a href="{{ route('storefront.product', $related->slug) }}" class="hover:text-indigo-600">
                                {{ $related->name }}
                            </a>
                        </h3>
                        <div class="text-sm font-black text-slate-900 mt-1">৳{{ number_format($related->new_price, 2) }}</div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection
