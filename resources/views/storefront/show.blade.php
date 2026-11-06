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
            <!-- Product Image -->
            <div class="relative rounded-2xl overflow-hidden bg-slate-100 aspect-square border border-slate-200/60 shadow-xs">
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                @if($product->discount_percentage)
                    <div class="absolute top-4 left-4 bg-rose-600 text-white text-xs font-black px-3 py-1 rounded-full shadow-md">
                        SAVE {{ $product->discount_percentage }}%
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

                <!-- Action Button -->
                <div class="pt-4 flex items-center gap-4">
                    <a href="{{ route('storefront.index') }}" class="px-5 py-3 rounded-xl border border-slate-300 text-slate-700 text-sm font-bold hover:bg-slate-100 transition inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        <span>Back to Catalog</span>
                    </a>
                    <a href="{{ route('admin.products.edit', $product) }}" class="px-5 py-3 rounded-xl bg-indigo-600 text-white text-sm font-bold hover:bg-indigo-700 transition shadow-sm inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        <span>Edit Product (Admin)</span>
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
