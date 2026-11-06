@extends('layouts.app')

@section('title', ($selectedCategory ? $selectedCategory->name . ' - ' : '') . 'All Products - ' . config('app.name', 'ShopEase'))

@section('content')
<div class="space-y-6">
    <!-- Top Header & Breadcrumbs Area (Matches User Design) -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4">
        <div>
            <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">
                {{ $selectedCategory ? $selectedCategory->name : 'All Products' }}
            </h1>
            <nav class="flex items-center gap-1.5 text-xs font-semibold text-slate-500 mt-1.5">
                <a href="{{ route('storefront.index') }}" class="hover:text-indigo-600 transition">Home</a>
                <span class="text-slate-400">&rsaquo;</span>
                @if($selectedCategory)
                    <a href="{{ route('storefront.products') }}" class="hover:text-indigo-600 transition">Products</a>
                    <span class="text-slate-400">&rsaquo;</span>
                    <span class="text-slate-800 font-bold">{{ $selectedCategory->name }}</span>
                @else
                    <span class="text-slate-800 font-bold">Products</span>
                @endif
            </nav>
        </div>

        <!-- Sort By Dropdown (Matches User Design) -->
        <div class="flex items-center gap-2 self-start sm:self-auto">
            <span class="text-xs font-bold text-slate-600">Sort by:</span>
            <form action="{{ route('storefront.products') }}" method="GET" id="catalog-sort-form">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
                <div class="relative">
                    <select name="sort" onchange="document.getElementById('catalog-sort-form').submit()" 
                            class="appearance-none pl-3.5 pr-8 py-2 bg-white rounded-xl border border-slate-200 text-xs font-bold text-slate-800 shadow-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none cursor-pointer">
                        <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Newest First</option>
                        <option value="price_asc" {{ $sort === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_desc" {{ $sort === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="name_asc" {{ $sort === 'name_asc' ? 'selected' : '' }}>Name: A to Z</option>
                    </select>
                    <span class="absolute inset-y-0 right-0 flex items-center pr-2.5 pointer-events-none text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </span>
                </div>
            </form>
        </div>
    </div>

    <!-- Main Two-Column Layout (Sidebar + 3-Col Product Grid) -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 items-start">
        <!-- Left Sidebar: Categories Navigation (Mobile-Type Collapsible Accordion) -->
        <aside class="lg:col-span-1">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden transition-all duration-300">
                <!-- Mobile-Type Header Accordion Toggle Button -->
                <button type="button" 
                        id="category-accordion-btn"
                        onclick="toggleCategoryMenu()"
                        class="w-full flex items-center justify-between p-4 sm:p-5 text-left hover:bg-slate-50/60 transition cursor-pointer group select-none"
                        aria-expanded="false"
                        aria-controls="category-dropdown-content">
                    <div class="flex items-center gap-3 min-w-0">
                        <span class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                            </svg>
                        </span>
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h2 class="font-extrabold text-slate-900 text-base sm:text-lg tracking-tight">Categories</h2>
                                @if($selectedCategory)
                                    <span class="px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-700 text-[11px] font-bold truncate max-w-[120px]">
                                        {{ $selectedCategory->name }}
                                    </span>
                                @endif
                            </div>
                            <p class="text-[11px] text-slate-400 font-medium truncate">
                                {{ $selectedCategory ? 'Filter applied • Tap arrow to change' : 'Tap arrow to view categories' }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        @if($selectedCategory)
                            <a href="{{ route('storefront.products', array_filter(['sort' => request('sort'), 'search' => request('search')])) }}" 
                               onclick="event.stopPropagation()"
                               class="text-[11px] font-bold text-rose-600 hover:text-rose-700 hover:underline mr-1">
                                Clear
                            </a>
                        @endif
                        <!-- Prominent Animated Toggle Arrow -->
                        <span id="category-accordion-arrow" 
                              class="w-8 h-8 rounded-xl bg-slate-100 text-slate-500 group-hover:bg-indigo-600 group-hover:text-white flex items-center justify-center transition-all duration-300 transform">
                            <svg class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </span>
                    </div>
                </button>

                <!-- Collapsible Category Content (Default NOT shown: "default not will view category") -->
                <div id="category-dropdown-content" class="hidden border-t border-slate-100 p-4 sm:p-5 pt-3 transition-all duration-300 space-y-1">
                    <!-- All Categories Link -->
                    <a href="{{ route('storefront.products', array_filter(['sort' => request('sort'), 'search' => request('search')])) }}" 
                       class="flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-bold transition group {{ !$selectedCategory ? 'bg-indigo-50/80 text-indigo-700 font-extrabold' : 'text-slate-700 hover:bg-slate-50 hover:text-indigo-600' }}">
                        <div class="flex items-center gap-2.5">
                            <span class="w-6 h-6 rounded-lg {{ !$selectedCategory ? 'bg-indigo-100 text-indigo-700' : 'bg-slate-100 text-slate-500 group-hover:text-indigo-600' }} flex items-center justify-center transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                            </span>
                            <span>All Categories</span>
                        </div>
                        <span class="text-slate-400 group-hover:text-indigo-600 transition font-bold">&rsaquo;</span>
                    </a>

                    <!-- Category Items -->
                    @foreach($categories as $category)
                        @php
                            $isActive = $selectedCategory && $selectedCategory->id === $category->id;
                            $hasSubs = !empty($category->subcategories) && $category->subcategories->count() > 0;
                        @endphp
                        <div class="space-y-1">
                            <div class="flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-bold transition group {{ $isActive ? 'bg-indigo-50/80 text-indigo-700 font-extrabold' : 'text-slate-700 hover:bg-slate-50 hover:text-indigo-600' }}">
                                <a href="{{ route('storefront.products', array_filter(['category' => $category->slug, 'sort' => request('sort'), 'search' => request('search')])) }}" 
                                   class="flex-1 flex items-center gap-2.5">
                                    <span class="w-6 h-6 rounded-lg {{ $isActive ? 'bg-indigo-100 text-indigo-700' : 'bg-slate-100 text-slate-500 group-hover:text-indigo-600' }} flex items-center justify-center transition shrink-0">
                                        @if(stripos($category->name, 'electr') !== false)
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                        @elseif(stripos($category->name, 'fash') !== false)
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                        @elseif(stripos($category->name, 'home') !== false || stripos($category->name, 'living') !== false)
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                        @elseif(stripos($category->name, 'sport') !== false)
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        @elseif(stripos($category->name, 'book') !== false)
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                        @else
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                        @endif
                                    </span>
                                    <span>{{ $category->name }}</span>
                                </a>

                                @if($hasSubs)
                                    <button type="button" 
                                            onclick="toggleSubcategories('subs-{{ $category->id }}', this)" 
                                            class="p-1 rounded-lg hover:bg-slate-200/60 transition text-slate-400 hover:text-indigo-600 cursor-pointer ml-2"
                                            title="Toggle subcategories">
                                        <svg class="w-3.5 h-3.5 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </button>
                                @else
                                    <a href="{{ route('storefront.products', array_filter(['category' => $category->slug, 'sort' => request('sort'), 'search' => request('search')])) }}" 
                                       class="text-slate-400 group-hover:text-indigo-600 transition font-bold">
                                        &rsaquo;
                                    </a>
                                @endif
                            </div>

                            @if($hasSubs)
                                <div id="subs-{{ $category->id }}" class="hidden pl-8 pr-2 py-1 space-y-1 border-l-2 border-indigo-100 ml-4">
                                    @foreach($category->subcategories as $sub)
                                        @php
                                            $isSubActive = request('subcategory') === $sub->slug || request('subcategory') == $sub->id;
                                        @endphp
                                        <a href="{{ route('storefront.products', array_filter(['category' => $category->slug, 'subcategory' => $sub->slug, 'sort' => request('sort'), 'search' => request('search')])) }}"
                                           class="block px-2.5 py-1.5 rounded-lg text-xs font-semibold transition {{ $isSubActive ? 'bg-indigo-100/70 text-indigo-700 font-bold' : 'text-slate-600 hover:bg-slate-100 hover:text-indigo-600' }}">
                                            • {{ $sub->name }}
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </aside>

        <!-- Right Side: 3-Columns Product Cards Grid (Matches User Image Exact Format) -->
        <div class="lg:col-span-3 space-y-6">
            @if($products->isEmpty())
                <div class="bg-white rounded-2xl border border-slate-100 p-12 text-center space-y-3">
                    <p class="text-sm font-bold text-slate-800">No products found in this category.</p>
                    <a href="{{ route('storefront.products') }}" class="inline-flex px-4 py-2 rounded-xl bg-indigo-600 text-white text-xs font-bold hover:bg-indigo-500 transition">
                        Show All Products
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($products as $product)
                        <div class="bg-white rounded-2xl border border-slate-100 shadow-xs hover:shadow-md transition p-4 flex flex-col justify-between group">
                            <!-- Product Image Canvas -->
                            <a href="{{ route('storefront.product', $product->slug) }}" class="aspect-square bg-[#F8F9FD] rounded-xl overflow-hidden flex items-center justify-center p-3 relative block">
                                <img src="{{ $product->image_url }}" 
                                     alt="{{ $product->name }}" 
                                     class="w-full h-full object-contain group-hover:scale-105 transition duration-300">
                            </a>

                            <!-- Card Info -->
                            <div class="mt-3.5 space-y-2 flex-1 flex flex-col justify-between">
                                <div>
                                    <h3 class="font-bold text-slate-900 text-base line-clamp-1 group-hover:text-indigo-600 transition">
                                        <a href="{{ route('storefront.product', $product->slug) }}">
                                            {{ $product->name }}
                                        </a>
                                    </h3>

                                    <!-- Price Row: Highlighted Current Price & Strikethrough Old Price -->
                                    <div class="flex items-baseline gap-2 mt-1">
                                        <span class="font-black text-rose-600 text-base">
                                            ৳{{ number_format($product->new_price, 0) }}
                                        </span>
                                        @if($product->old_price && $product->old_price > $product->new_price)
                                            <span class="text-xs text-slate-400 line-through">
                                                ৳{{ number_format($product->old_price, 0) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Add to Cart Action Button -->
                                <button type="button" 
                                        onclick="window.addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ (float) $product->new_price }}, '{{ addslashes($product->image_url) }}', '{{ $product->slug }}')"
                                        class="w-full mt-3 py-2.5 px-4 rounded-xl bg-gradient-to-r from-[#5046E5] to-[#6366F1] hover:from-[#4338CA] hover:to-[#4F46E5] text-white text-xs font-bold shadow-md shadow-indigo-100 transition active:scale-95 flex items-center justify-center gap-1.5 cursor-pointer">
                                    <span>Add to Cart</span>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination Links -->
                <div class="pt-4">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    function toggleCategoryMenu() {
        const content = document.getElementById('category-dropdown-content');
        const arrow = document.getElementById('category-accordion-arrow');
        const btn = document.getElementById('category-accordion-btn');
        if (!content || !arrow) return;

        const isHidden = content.classList.contains('hidden');
        if (isHidden) {
            content.classList.remove('hidden');
            arrow.classList.add('rotate-180', 'bg-indigo-600', 'text-white');
            arrow.classList.remove('bg-slate-100', 'text-slate-500');
            if (btn) btn.setAttribute('aria-expanded', 'true');
        } else {
            content.classList.add('hidden');
            arrow.classList.remove('rotate-180', 'bg-indigo-600', 'text-white');
            arrow.classList.add('bg-slate-100', 'text-slate-500');
            if (btn) btn.setAttribute('aria-expanded', 'false');
        }
    }

    function toggleSubcategories(elementId, button) {
        const el = document.getElementById(elementId);
        if (!el) return;
        const isHidden = el.classList.contains('hidden');
        const arrowSvg = button ? button.querySelector('svg') : null;
        if (isHidden) {
            el.classList.remove('hidden');
            if (arrowSvg) arrowSvg.classList.add('rotate-90');
        } else {
            el.classList.add('hidden');
            if (arrowSvg) arrowSvg.classList.remove('rotate-90');
        }
    }
</script>
@endpush
@endsection

