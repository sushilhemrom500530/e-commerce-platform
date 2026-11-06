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
<body class="flex flex-col min-h-screen text-slate-800 antialiased">
    @if(request()->routeIs('admin.*'))
        <!-- Admin Management Portal Header -->
        <header class="sticky top-0 z-40 bg-slate-900 text-white border-b border-slate-800 shadow-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center gap-6">
                        <a href="{{ route('admin.products.index') }}" class="flex items-center gap-2.5 font-extrabold text-lg tracking-tight">
                            <span class="w-8 h-8 rounded-lg bg-indigo-600 text-white flex items-center justify-center font-black text-sm shadow">PL</span>
                            <span>Market<span class="text-indigo-400">Hub</span></span>
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

                        @auth
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="px-2.5 py-1.5 rounded-lg text-slate-400 hover:text-rose-400 text-xs font-semibold transition">Logout</button>
                            </form>
                        @endauth
                    </div>
                </div>
            </div>
        </header>
    @else
        <!-- Customer Storefront Header -->
        <header class="sticky top-0 z-40 bg-white/95 backdrop-blur border-b border-slate-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16 gap-4">
                    <!-- Brand Logo -->
                    <a href="{{ route('storefront.index') }}" class="flex items-center gap-2.5 text-indigo-600 font-extrabold text-xl tracking-tight flex-shrink-0">
                        <span class="w-9 h-9 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-black shadow-md shadow-indigo-200">PL</span>
                        <span class="text-slate-900">Market<span class="text-indigo-600">Hub</span></span>
                    </a>

                    <!-- Search Bar -->
                    <form action="{{ route('storefront.index') }}" method="GET" class="hidden sm:flex flex-1 max-w-md mx-4">
                        <div class="relative w-full">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </span>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products, brands, models..." class="w-full pl-10 pr-4 py-2 rounded-xl bg-slate-100 border-none text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:outline-none transition">
                        </div>
                    </form>

                    <!-- Navigation Links -->
                    <div class="flex items-center gap-3">
                        <a href="{{ route('storefront.index') }}" class="text-xs font-bold text-slate-700 hover:text-indigo-600 transition hidden md:inline">Catalog</a>

                        @auth
                            <div class="flex items-center gap-2.5 pl-2 border-l border-slate-200">
                                <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-xs uppercase">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                                <div class="hidden sm:block text-left">
                                    <span class="block text-xs font-bold text-slate-800 truncate max-w-[120px]">{{ auth()->user()->name }}</span>
                                    <span class="block text-[10px] text-slate-400 uppercase font-semibold">{{ auth()->user()->role ?? 'Customer' }}</span>
                                </div>
                                @if(auth()->user()->role === 'admin')
                                    <a href="{{ route('admin.products.index') }}" class="ml-1 px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 text-xs font-bold hover:bg-indigo-100 transition">
                                        Admin Dashboard
                                    </a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}" class="inline ml-1">
                                    @csrf
                                    <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 transition" title="Sign Out">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    </button>
                                </form>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="px-3.5 py-2 text-xs font-bold text-slate-700 hover:text-indigo-600 transition">
                                Sign In
                            </a>
                            <a href="{{ route('register') }}" class="px-3.5 py-2 rounded-xl bg-slate-900 text-white text-xs font-bold hover:bg-slate-800 transition shadow-xs">
                                Register
                            </a>
                            <a href="{{ route('admin.products.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-indigo-200 bg-indigo-50/70 text-indigo-700 text-xs font-bold hover:bg-indigo-100 transition" title="Access Admin Portal">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                <span>Admin Portal</span>
                            </a>
                        @endauth
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
