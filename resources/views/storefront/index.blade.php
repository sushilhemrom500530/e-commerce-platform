@extends('layouts.app')

@section('content')
<div class="space-y-12">
    <!-- Hero Banner (100% Real Responsive HTML/CSS with Exact Design Match) -->
    <div class="relative rounded-3xl sm:rounded-[2.5rem] bg-gradient-to-r from-[#DFD7FE] via-[#DDD4FE] to-[#D5C9FC] overflow-hidden border border-purple-100/60 shadow-xs min-h-[380px] sm:min-h-[460px] flex items-center">
        <!-- Exact 3D AirPods Graphic on Right -->
        <div class="absolute right-0 top-0 bottom-0 w-full md:w-3/5 lg:w-1/2 flex items-center justify-end pointer-events-none">
            <img src="{{ asset('images/hero-airpods-blend.png') }}" 
                 alt="3D Wireless Earbuds in Charging Case" 
                 class="h-full w-auto object-cover object-left md:object-right select-none opacity-90 sm:opacity-100">
        </div>

        <!-- Left Content: Real Semantic HTML & Tailwind CSS -->
        <div class="relative z-10 py-12 sm:py-20 px-6 sm:px-14 max-w-xl space-y-6">
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-[#17114A] tracking-tight leading-[1.08]">
                Smart Shopping<br>
                <span>Better Living</span>
            </h1>
            <p class="text-[#4E477E] text-sm sm:text-base font-medium max-w-md leading-relaxed">
                Discover amazing products with<br class="hidden sm:inline"> best prices and fast delivery.
            </p>
            <div class="pt-2">
                <a href="{{ route('storefront.products') }}" class="inline-flex items-center gap-2.5 px-7 py-3.5 rounded-2xl bg-[#5945F7] hover:bg-[#4C37E8] text-white font-bold text-sm shadow-lg shadow-indigo-400/30 transition hover:scale-105 duration-200">
                    <span>Shop Now</span>
                    <span class="text-base font-bold">→</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Trust Badges Strip (Reference Image Match) -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 py-6 px-6 sm:px-10 bg-white rounded-2xl border border-slate-100 shadow-xs">
        <!-- Fast Delivery -->
        <div class="flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-800 flex-shrink-0">
                <svg class="w-6 h-6 text-slate-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
            </div>
            <div>
                <h4 class="font-extrabold text-xs text-slate-900 tracking-tight">Fast Delivery</h4>
                <p class="text-[11px] text-slate-400 font-medium">Across Bangladesh</p>
            </div>
        </div>

        <!-- Secure Payment -->
        <div class="flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-800 flex-shrink-0">
                <svg class="w-6 h-6 text-slate-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            </div>
            <div>
                <h4 class="font-extrabold text-xs text-slate-900 tracking-tight">Secure Payment</h4>
                <p class="text-[11px] text-slate-400 font-medium">100% Secure</p>
            </div>
        </div>

        <!-- Easy Returns -->
        <div class="flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-800 flex-shrink-0">
                <svg class="w-6 h-6 text-slate-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            </div>
            <div>
                <h4 class="font-extrabold text-xs text-slate-900 tracking-tight">Easy Returns</h4>
                <p class="text-[11px] text-slate-400 font-medium">7 Days Return</p>
            </div>
        </div>

        <!-- 24/7 Support -->
        <div class="flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-800 flex-shrink-0">
                <svg class="w-6 h-6 text-slate-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
            <div>
                <h4 class="font-extrabold text-xs text-slate-900 tracking-tight">24/7 Support</h4>
                <p class="text-[11px] text-slate-400 font-medium">Always Here</p>
            </div>
        </div>
    </div>

    <!-- Top Categories Section (Direct Links to Filtered Products) -->
    <section class="space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-black text-slate-900 tracking-tight">Top Categories</h2>
            <a href="{{ route('storefront.products') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-indigo-600 hover:text-indigo-700 transition">
                <span>View All</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <!-- 1. Laptops / Electronics Card -->
            <a href="{{ route('storefront.products', ['category' => 'electronics']) }}" class="group text-center space-y-3 block">
                <div class="aspect-[4/3] rounded-3xl bg-[#EEF2FF] border border-indigo-100/60 p-4 flex items-center justify-center transition group-hover:shadow-md group-hover:-translate-y-1 duration-300 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=500&auto=format&fit=crop&q=80" 
                         alt="Laptops & Tech" 
                         class="w-full h-full object-contain group-hover:scale-105 transition duration-300">
                </div>
                <h3 class="font-bold text-sm text-slate-800 group-hover:text-indigo-600 transition">Laptops</h3>
            </a>

            <!-- 2. Mobiles Card -->
            <a href="{{ route('storefront.products', ['category' => 'electronics']) }}" class="group text-center space-y-3 block">
                <div class="aspect-[4/3] rounded-3xl bg-[#E0F2FE] border border-sky-100/60 p-4 flex items-center justify-center transition group-hover:shadow-md group-hover:-translate-y-1 duration-300 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?w=500&auto=format&fit=crop&q=80" 
                         alt="Mobiles" 
                         class="w-full h-full object-contain group-hover:scale-105 transition duration-300">
                </div>
                <h3 class="font-bold text-sm text-slate-800 group-hover:text-indigo-600 transition">Mobiles</h3>
            </a>

            <!-- 3. Watches Card -->
            <a href="{{ route('storefront.products', ['category' => 'sports']) }}" class="group text-center space-y-3 block">
                <div class="aspect-[4/3] rounded-3xl bg-[#FCE7F3] border border-pink-100/60 p-4 flex items-center justify-center transition group-hover:shadow-md group-hover:-translate-y-1 duration-300 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=500&auto=format&fit=crop&q=80" 
                         alt="Watches" 
                         class="w-full h-full object-contain group-hover:scale-105 transition duration-300">
                </div>
                <h3 class="font-bold text-sm text-slate-800 group-hover:text-indigo-600 transition">Watches</h3>
            </a>

            <!-- 4. Fashion Card -->
            <a href="{{ route('storefront.products', ['category' => 'fashion']) }}" class="group text-center space-y-3 block">
                <div class="aspect-[4/3] rounded-3xl bg-[#FEF3C7] border border-amber-100/60 p-4 flex items-center justify-center transition group-hover:shadow-md group-hover:-translate-y-1 duration-300 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=500&auto=format&fit=crop&q=80" 
                         alt="Fashion" 
                         class="w-full h-full object-contain group-hover:scale-105 transition duration-300">
                </div>
                <h3 class="font-bold text-sm text-slate-800 group-hover:text-indigo-600 transition">Fashion</h3>
            </a>
        </div>
    </section>

    <!-- 5 Featured Category Product Sections (Reduced & Focused) -->
    <div id="products-catalog" class="space-y-16 pt-4">
        @if(request('search'))
            <div class="flex items-center justify-between p-4 bg-indigo-50/70 border border-indigo-100 rounded-2xl">
                <p class="text-xs font-semibold text-indigo-900">
                    Showing search results for: <span class="font-bold font-mono">"{{ request('search') }}"</span>
                </p>
                <a href="{{ route('storefront.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 underline">Clear Filter</a>
            </div>
        @endif

        @forelse($homeCategories as $category)
            <section class="space-y-6">
                <!-- Category Section Header: Clickable Category Name & Direct Link -->
                <div class="flex flex-col sm:flex-row sm:items-end justify-between border-b border-slate-200 pb-4 gap-3">
                    <div>
                        <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-indigo-600">
                            <span>Featured Category</span>
                            <span>&bull;</span>
                            <span>{{ $category->products_count }} {{ Str::plural('Product', $category->products_count) }}</span>
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight mt-1">
                            <a href="{{ route('storefront.products', ['category' => $category->slug]) }}" class="hover:text-indigo-600 transition inline-flex items-center gap-2">
                                <span>{{ $category->name }}</span>
                                <span class="text-indigo-400 text-lg">&rsaquo;</span>
                            </a>
                        </h2>
                        @if($category->description)
                            <p class="text-xs text-slate-500 mt-1 max-w-xl">{{ $category->description }}</p>
                        @endif
                    </div>

                    <!-- Direct View All Button for this category -->
                    <a href="{{ route('storefront.products', ['category' => $category->slug]) }}" 
                       class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl border border-indigo-200 bg-indigo-50/80 text-indigo-700 text-xs font-bold hover:bg-indigo-600 hover:text-white transition shadow-xs self-start sm:self-auto">
                        <span>View All {{ $category->name }}</span>
                        <span>&rarr;</span>
                    </a>
                </div>

                <!-- Products Grid for this Category: All Clickable to Product Details -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($category->products as $product)
                        <div class="group bg-white rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition flex flex-col justify-between overflow-hidden">
                            <!-- Image Frame: Clickable to Product Details -->
                            <a href="{{ route('storefront.product', $product->slug) }}" class="relative aspect-[4/3] bg-slate-100 overflow-hidden block">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                @if($product->discount_percentage)
                                    <span class="absolute top-3 left-3 bg-rose-600 text-white text-[11px] font-bold px-2 py-0.5 rounded-full shadow-sm">
                                        -{{ $product->discount_percentage }}%
                                    </span>
                                @endif
                                @if($product->subcategory)
                                    <span class="absolute top-3 right-3 bg-white/90 backdrop-blur text-slate-700 text-[10px] font-semibold px-2 py-0.5 rounded-md shadow-xs">
                                        {{ $product->subcategory->name }}
                                    </span>
                                @endif
                            </div>

                            <!-- Card Body: Clickable to Product Details -->
                            <div class="p-4 flex-1 flex flex-col justify-between space-y-3">
                                <div>
                                    <span class="text-[11px] font-semibold text-indigo-600 uppercase tracking-wider block">
                                        <a href="{{ route('storefront.products', ['category' => $category->slug]) }}" class="hover:underline">
                                            {{ $category->name }}
                                        </a>
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
                                    <div class="flex items-center gap-1.5">
                                        <button type="button" 
                                                onclick="window.showAddToCartToast('{{ addslashes($product->name) }}')"
                                                class="p-2 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white transition cursor-pointer" 
                                                title="Add to Cart">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                        </button>
                                        <a href="{{ route('storefront.product', $product->slug) }}" class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg bg-slate-900 text-white text-xs font-bold hover:bg-indigo-600 transition shadow-xs">
                                            Details
                                        </a>
                                    </div>
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
            </div>
        @endforelse
    </div>
</div>
@endsection
