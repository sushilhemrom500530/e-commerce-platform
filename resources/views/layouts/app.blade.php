<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name', 'E-Commerce Platform') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#F5F3FF',
                            100: '#EDE9FE',
                            200: '#DDD6FE',
                            300: '#C4B5FD',
                            400: '#A78BFA',
                            500: '#5945F7',
                            600: '#5046E5',
                            700: '#4338CA',
                            800: '#3730A3',
                            900: '#312E81',
                            DEFAULT: '#5945F7',
                        }
                    }
                }
            }
        };
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #5945F7;
            --primary-dark: #4338CA;
            --primary-light: #EEF2FF;
        }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="flex flex-col min-h-screen text-slate-800 antialiased bg-white">
    <!-- ShopEase Storefront Full-Width Header with Smart Scroll Animation -->
    <header id="storefront-navbar" class="sticky top-0 z-50 w-full bg-white/95 backdrop-blur border-b border-slate-100 shadow-xs transition-transform duration-300 ease-in-out">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
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
                        <a href="{{ route('storefront.products') }}" class="transition hover:text-indigo-600 {{ request()->routeIs('storefront.products') ? 'text-indigo-600 font-bold' : '' }}">Shop</a>
                        
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
                                    <a href="{{ route('storefront.products', ['category' => $navCat->slug]) }}" class="block px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition">
                                        {{ $navCat->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        <a href="{{ route('storefront.about') }}" class="transition hover:text-indigo-600 {{ request()->routeIs('storefront.about') ? 'text-indigo-600 font-bold' : '' }}">About</a>
                        <a href="{{ route('storefront.contact') }}" class="transition hover:text-indigo-600 {{ request()->routeIs('storefront.contact') ? 'text-indigo-600 font-bold' : '' }}">Contact</a>
                    </nav>

                    <!-- Right Side Icons & Actions -->
                    <div class="flex items-center gap-3 sm:gap-4">
                        <!-- Search Form / Icon -->
                        <form action="{{ route('storefront.products') }}" method="GET" class="relative hidden sm:block">
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

                        <!-- User Authentication Status: Login Button OR User Avatar with Hover Dropdown -->
                        @guest
                            <!-- Login Option (When not logged in) -->
                            <a href="{{ route('login') }}" 
                               class="inline-flex items-center gap-1.5 px-3.5 py-1.5 sm:px-4 sm:py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold transition shadow-xs hover:shadow active:scale-95">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                                <span>Login</span>
                            </a>
                        @endguest

                        @auth
                            <!-- Logged In User Avatar with Hover Dropdown -->
                            <div class="relative group">
                                <button type="button" 
                                        class="flex items-center p-0.5 rounded-full ring-2 ring-indigo-500/20 group-hover:ring-indigo-600 transition focus:outline-none cursor-pointer"
                                        aria-label="User account menu">
                                    @if(auth()->user()->profile_url)
                                        <img src="{{ auth()->user()->profile_url }}" 
                                             alt="{{ auth()->user()->name }}" 
                                             class="w-8 h-8 sm:w-9 sm:h-9 rounded-full object-cover">
                                    @else
                                        <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-gradient-to-tr from-indigo-600 to-purple-600 text-white font-bold text-xs flex items-center justify-center shadow-xs">
                                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </button>

                                <!-- Hover Dropdown Menu: Displays Admin Panel, Profile, and Logout -->
                                <div class="absolute right-0 top-full pt-2 w-56 hidden group-hover:block hover:block z-50">
                                    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 p-2 text-left animate-in fade-in duration-150">
                                        <!-- User Header -->
                                        <div class="px-3 py-2.5 border-b border-slate-100 mb-1">
                                            <p class="text-xs font-bold text-slate-900 truncate">{{ auth()->user()->name }}</p>
                                            <p class="text-[11px] text-slate-400 truncate">{{ auth()->user()->email }}</p>
                                            <div class="mt-1 flex items-center gap-1.5">
                                                <span class="inline-block px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider {{ auth()->user()->isAdmin() ? 'bg-purple-100 text-purple-700' : 'bg-slate-100 text-slate-600' }}">
                                                    {{ auth()->user()->role ?? 'User' }}
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Dropdown Action Buttons -->
                                        <div class="space-y-0.5">
                                            <!-- Admin Panel Button -->
                                            <a href="{{ route('admin.dashboard') }}" 
                                               class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition group/item">
                                                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                                                <span>Admin Panel</span>
                                            </a>

                                            <!-- Profile Button -->
                                            <a href="{{ route('profile') }}" 
                                               class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition group/item">
                                                <svg class="w-4 h-4 text-slate-400 group-hover/item:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                                <span>Profile</span>
                                            </a>

                                            <!-- Logout Button -->
                                            <form method="POST" action="{{ route('logout') }}" class="pt-1 border-t border-slate-100">
                                                @csrf
                                                <button type="submit" 
                                                        class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-semibold text-rose-600 hover:bg-rose-50 transition cursor-pointer text-left">
                                                    <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                                    <span>Logout</span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endauth

                        <!-- Mobile Hamburger Menu Button (visible on small devices) -->
                        <button type="button" 
                                id="mobile-menu-btn" 
                                onclick="toggleMobileMenu()" 
                                class="md:hidden p-2 rounded-xl text-slate-700 hover:text-indigo-600 hover:bg-slate-100 transition focus:outline-none cursor-pointer"
                                aria-label="Toggle mobile menu"
                                aria-expanded="false">
                            <svg id="hamburger-icon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                            <svg id="close-icon" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Navigation Menu Dropdown Panel (Displays all nav items on small devices) -->
            <div id="mobile-menu" class="hidden md:hidden border-t border-slate-100 bg-white/98 backdrop-blur-md px-4 sm:px-6 pt-3 pb-6 shadow-xl transition-all duration-300">
                <!-- Mobile Search (< sm) -->
                <div class="sm:hidden mb-4 pt-1">
                    <form action="{{ route('storefront.products') }}" method="GET" class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..." 
                               class="w-full pl-9 pr-4 py-2 rounded-xl bg-slate-100 text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </span>
                    </form>
                </div>

                <!-- Nav Menu Items (Home, Shop, Categories, About, Contact) -->
                <nav class="space-y-1 text-sm font-semibold text-slate-700">
                    <!-- Home -->
                    <a href="{{ route('storefront.index') }}" 
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('storefront.index') && !request('category') ? 'bg-indigo-50 text-indigo-600 font-bold' : 'hover:bg-slate-50 hover:text-indigo-600' }}">
                        <span>Home</span>
                        <span class="text-xs text-slate-400">&rsaquo;</span>
                    </a>

                    <!-- Shop -->
                    <a href="{{ route('storefront.products') }}" 
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('storefront.products') && !request('category') ? 'bg-indigo-50 text-indigo-600 font-bold' : 'hover:bg-slate-50 hover:text-indigo-600' }}">
                        <span>Shop</span>
                        <span class="text-xs text-slate-400">&rsaquo;</span>
                    </a>

                    <!-- Categories (With Expandable Sub-items) -->
                    <div>
                        <button type="button" 
                                onclick="toggleMobileCategories()" 
                                class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-left transition hover:bg-slate-50 hover:text-indigo-600 cursor-pointer {{ request('category') ? 'text-indigo-600 font-bold' : '' }}">
                            <span>Categories</span>
                            <svg id="mobile-categories-arrow" class="w-4 h-4 text-slate-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div id="mobile-categories-dropdown" class="hidden pl-4 pr-2 py-1 space-y-1 border-l-2 border-indigo-100 ml-4 my-1">
                            <a href="{{ route('storefront.products') }}" class="block px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-600 hover:text-indigo-600 hover:bg-indigo-50/50">
                                All Categories
                            </a>
                            @php
                                $mobileNavCategories = \App\Models\Category::where('is_active', true)->orderBy('name')->take(8)->get();
                            @endphp
                            @foreach($mobileNavCategories as $mCat)
                                <a href="{{ route('storefront.products', ['category' => $mCat->slug]) }}" 
                                   class="block px-3 py-1.5 rounded-lg text-xs font-semibold {{ request('category') === $mCat->slug ? 'text-indigo-600 font-bold bg-indigo-50' : 'text-slate-600 hover:text-indigo-600 hover:bg-indigo-50/50' }}">
                                    {{ $mCat->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- About -->
                    <a href="{{ route('storefront.about') }}" 
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('storefront.about') ? 'bg-indigo-50 text-indigo-600 font-bold' : 'hover:bg-slate-50 hover:text-indigo-600' }}">
                        <span>About</span>
                        <span class="text-xs text-slate-400">&rsaquo;</span>
                    </a>

                    <!-- Contact -->
                    <a href="{{ route('storefront.contact') }}" 
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('storefront.contact') ? 'bg-indigo-50 text-indigo-600 font-bold' : 'hover:bg-slate-50 hover:text-indigo-600' }}">
                        <span>Contact</span>
                        <span class="text-xs text-slate-400">&rsaquo;</span>
                    </a>
                </nav>
            </div>
        </header>

    <!-- Top-Right Toast Notifications -->
    @include('partials.toast')

    <!-- Main Content -->
    <main class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="mt-auto border-t border-slate-200 bg-white py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-black text-sm shadow-md shadow-indigo-100">SE</span>
                    <span class="font-black text-slate-900 text-lg tracking-tight">Shop<span class="text-indigo-600">Ease</span></span>
                </div>
                <div class="flex flex-wrap items-center justify-center gap-6 text-xs font-semibold text-slate-500">
                    <a href="{{ route('storefront.index') }}" class="hover:text-indigo-600 transition">Home</a>
                    <a href="{{ route('storefront.products') }}" class="hover:text-indigo-600 transition">Shop</a>
                    <a href="{{ route('storefront.about') }}" class="hover:text-indigo-600 transition">About Us</a>
                    <a href="{{ route('storefront.contact') }}" class="hover:text-indigo-600 transition">Contact</a>
                    <a href="{{ route('admin.products.index') }}" class="text-slate-400 hover:text-indigo-600 transition">Admin Portal &rarr;</a>
                </div>
            </div>
            <div class="border-t border-slate-100 pt-6 text-center text-xs text-slate-400">
                <p>&copy; {{ date('Y') }} {{ config('app.name', 'ShopEase') }}. All rights reserved. Built with pride for Bangladesh.</p>
            </div>
        </div>
    </footer>

    <!-- Smart Header: Hide on scroll down, smooth animated reveal on scroll up -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const navbar = document.getElementById('storefront-navbar');
            if (!navbar) return;

            let lastScrollY = window.scrollY;
            const threshold = 6;

            window.addEventListener('scroll', function () {
                const currentScrollY = window.scrollY;

                if (Math.abs(currentScrollY - lastScrollY) < threshold) {
                    return;
                }

                if (currentScrollY <= 20) {
                    navbar.classList.remove('-translate-y-full');
                } else if (currentScrollY > lastScrollY && currentScrollY > 80) {
                    navbar.classList.add('-translate-y-full');
                } else if (currentScrollY < lastScrollY) {
                    navbar.classList.remove('-translate-y-full');
                }

                lastScrollY = currentScrollY;
            }, { passive: true });
        });

        window.showAddToCartToast = function(productName) {
            const toast = document.getElementById('cart-toast');
            const toastTitle = document.getElementById('cart-toast-title');
            if (toast && toastTitle) {
                toastTitle.textContent = productName + ' added to cart!';
                toast.classList.remove('translate-y-24', 'opacity-0');
                setTimeout(() => {
                    toast.classList.add('translate-y-24', 'opacity-0');
                }, 2500);
            }
        };

        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            const hamburger = document.getElementById('hamburger-icon');
            const close = document.getElementById('close-icon');
            const btn = document.getElementById('mobile-menu-btn');
            if (!menu) return;

            const isHidden = menu.classList.contains('hidden');
            if (isHidden) {
                menu.classList.remove('hidden');
                if (hamburger) hamburger.classList.add('hidden');
                if (close) close.classList.remove('hidden');
                if (btn) btn.setAttribute('aria-expanded', 'true');
            } else {
                menu.classList.add('hidden');
                if (hamburger) hamburger.classList.remove('hidden');
                if (close) close.classList.add('hidden');
                if (btn) btn.setAttribute('aria-expanded', 'false');
            }
        }

        function toggleMobileCategories() {
            const dropdown = document.getElementById('mobile-categories-dropdown');
            const arrow = document.getElementById('mobile-categories-arrow');
            if (!dropdown) return;

            const isHidden = dropdown.classList.contains('hidden');
            if (isHidden) {
                dropdown.classList.remove('hidden');
                if (arrow) arrow.classList.add('rotate-180');
            } else {
                dropdown.classList.add('hidden');
                if (arrow) arrow.classList.remove('rotate-180');
            }
        }
    </script>

    <!-- Global Cart Toast Notification -->
    <div id="cart-toast" class="fixed bottom-6 right-6 z-50 bg-slate-900/95 backdrop-blur text-white px-5 py-3.5 rounded-2xl shadow-2xl flex items-center gap-3 transform translate-y-24 opacity-0 transition-all duration-300 pointer-events-none border border-slate-700/50">
        <div class="w-8 h-8 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center font-bold text-sm">✓</div>
        <div>
            <p class="text-xs font-bold" id="cart-toast-title">Added to cart!</p>
            <p class="text-[11px] text-slate-400">Shopping cart item updated</p>
        </div>
    </div>
    @stack('scripts')
</body>
</html>
