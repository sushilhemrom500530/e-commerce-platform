<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name', 'E-Commerce Platform') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="flex flex-col min-h-screen text-slate-800 antialiased bg-white">
    @if(request()->routeIs('admin.*'))
        <!-- Admin Management Portal Header -->
        <header class="sticky top-0 z-40 bg-slate-900 text-white border-b border-slate-800 shadow-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center gap-6">
                        <a href="{{ route('admin.products.index') }}" class="flex items-center gap-2.5 font-extrabold text-lg tracking-tight">
                            <span class="w-8 h-8 rounded-lg bg-indigo-600 text-white flex items-center justify-center font-black text-sm shadow">SE</span>
                            <span>Shop<span class="text-indigo-400">Ease</span></span>
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-indigo-500/20 text-indigo-300 border border-indigo-400/30">Admin Panel</span>
                        </a>

                        <nav class="hidden md:flex items-center gap-1 text-sm font-semibold">
                            <a href="{{ route('admin.products.index') }}" class="px-3 py-1.5 rounded-lg transition {{ request()->routeIs('admin.products.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">Products</a>
                            <a href="{{ route('admin.categories.index') }}" class="px-3 py-1.5 rounded-lg transition {{ request()->routeIs('admin.categories.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">Categories</a>
                            <a href="{{ route('admin.subcategories.index') }}" class="px-3 py-1.5 rounded-lg transition {{ request()->routeIs('admin.subcategories.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">Subcategories</a>
                        </nav>
                    </div>

                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-indigo-600 text-white text-xs font-bold hover:bg-indigo-500 transition shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            <span>Add Product</span>
                        </a>

                        <a href="{{ route('storefront.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-800 text-slate-300 hover:text-white hover:bg-slate-700 text-xs font-semibold transition border border-slate-700">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <span>View Storefront</span>
                        </a>
                    </div>
                </div>
            </div>
        </header>
    @else
        <!-- ShopEase Storefront Floating Header (Matches User Design) -->
        <header class="sticky top-3 z-40 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
            <div class="bg-white/95 backdrop-blur rounded-2xl sm:rounded-3xl border border-slate-100 shadow-sm px-6 sm:px-8">
                <div class="flex items-center justify-between h-16 sm:h-20">
                    <!-- Brand Logo -->
                    <a href="{{ route('storefront.index') }}" class="flex items-center gap-2.5 text-slate-900 font-extrabold text-2xl tracking-tight">
                        <span class="w-9 h-9 rounded-xl bg-indigo-600 text-white flex items-center justify-center shadow-md shadow-indigo-200">
                            <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M16 7a4 4 0 00-8 0v1H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2v-9a2 2 0 00-2-2h-3V7zm-6 1V7a2 2 0 114 0v1h-4zm-1 6a1 1 0 112 0 3 3 0 006 0 1 1 0 112 0 5 5 0 01-10 0z"/>
                            </svg>
                        </span>
                        <span class="font-black text-slate-900 text-xl tracking-tight">Shop<span class="text-indigo-600">Ease</span></span>
                    </a>

                    <!-- Middle Navigation Links -->
                    <nav class="hidden md:flex items-center gap-8 text-sm font-semibold text-slate-600">
                        <a href="{{ route('storefront.index') }}" class="transition hover:text-indigo-600 {{ request()->routeIs('storefront.index') && !request('category') ? 'text-indigo-600 font-bold' : '' }}">Home</a>
                        <a href="#products-catalog" class="transition hover:text-indigo-600">Shop</a>
                        
                        <!-- Categories Dropdown -->
                        <div class="relative group">
                            <button type="button" class="inline-flex items-center gap-1 transition hover:text-indigo-600 py-2">
                                <span>Categories</span>
                                <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-indigo-600 transition group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div class="absolute left-0 mt-0 w-48 bg-white rounded-2xl shadow-xl border border-slate-100 p-2 hidden group-hover:block transition z-50">
                                @php
                                    $navCategories = \App\Models\Category::where('is_active', true)->orderBy('name')->take(6)->get();
                                @endphp
                                @foreach($navCategories as $navCat)
                                    <a href="{{ route('storefront.index', ['category' => $navCat->slug]) }}" class="block px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition">
                                        {{ $navCat->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        <a href="#about-section" class="transition hover:text-indigo-600">About</a>
                        <a href="#contact-section" class="transition hover:text-indigo-600">Contact</a>
                    </nav>

                    <!-- Right Side Icons & Actions -->
                    <div class="flex items-center gap-4">
                        <!-- Search Form / Icon -->
                        <form action="{{ route('storefront.index') }}" method="GET" class="relative hidden sm:block">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..." class="pl-9 pr-3 py-1.5 rounded-full bg-slate-100 text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:outline-none w-36 focus:w-56 transition-all duration-300">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </span>
                        </form>

                        <!-- Cart Icon with Badge (From Design) -->
                        <div class="relative cursor-pointer text-slate-700 hover:text-indigo-600 transition p-1" title="Shopping Cart">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            <span class="absolute -top-1 -right-1 bg-rose-500 text-white text-[10px] font-black w-4 h-4 rounded-full flex items-center justify-center shadow-xs">3</span>
                        </div>

                        <!-- Admin Panel Shortcut -->
                        <a href="{{ route('admin.products.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-indigo-200 bg-indigo-50/70 text-indigo-700 text-xs font-bold hover:bg-indigo-600 hover:text-white transition" title="Go to Admin Panel">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span class="hidden sm:inline">Admin Panel</span>
                        </a>
                    </div>
                </div>
            </div>
        </header>

                        <!-- Admin Panel Shortcut -->
                        <a href="{{ route('admin.products.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-indigo-200 bg-indigo-50/70 text-indigo-700 text-xs font-bold hover:bg-indigo-600 hover:text-white transition" title="Go to Admin Panel">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span class="hidden sm:inline">Admin Panel</span>
                        </a>
                    </div>
                </div>
            </div>
        </header>
    @endif

    <!-- Flash Alerts -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
        @if(session('success'))
            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm font-medium flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-rose-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="mt-auto border-t border-slate-200 bg-white py-6">
        <div class="max-w-7xl mx-auto px-4 text-center text-xs text-slate-500">
            <p>&copy; {{ date('Y') }} {{ config('app.name', 'E-Commerce Platform') }}. Built with Laravel 11/12 & Tailwind CSS.</p>
        </div>
    </footer>
    @stack('scripts')
</body>
</html>
