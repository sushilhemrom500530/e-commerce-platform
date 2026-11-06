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
    <!-- Navigation Bar -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-8">
                    <a href="{{ route('storefront.index') }}" class="flex items-center gap-2.5 text-indigo-600 font-extrabold text-xl tracking-tight">
                        <span class="w-9 h-9 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-black shadow-md shadow-indigo-200">PL</span>
                        <span class="text-slate-900">Market<span class="text-indigo-600">Hub</span></span>
                    </a>
                    <nav class="hidden md:flex items-center gap-1">
                        <a href="{{ route('storefront.index') }}" class="px-3.5 py-2 rounded-lg text-sm font-semibold transition {{ request()->routeIs('storefront.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">Storefront</a>
                        <span class="text-slate-300">|</span>
                        <a href="{{ route('admin.products.index') }}" class="px-3.5 py-2 rounded-lg text-sm font-semibold transition {{ request()->routeIs('admin.products.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">Products</a>
                        <a href="{{ route('admin.categories.index') }}" class="px-3.5 py-2 rounded-lg text-sm font-semibold transition {{ request()->routeIs('admin.categories.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">Categories</a>
                        <a href="{{ route('admin.subcategories.index') }}" class="px-3.5 py-2 rounded-lg text-sm font-semibold transition {{ request()->routeIs('admin.subcategories.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">Subcategories</a>
                    </nav>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-indigo-600 text-white text-xs sm:text-sm font-bold shadow-sm shadow-indigo-300 hover:bg-indigo-700 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>Add Product</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

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
