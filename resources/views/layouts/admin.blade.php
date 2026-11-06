<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#F3F5FA]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'ShopEase Admin' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            500: '#5945F7',
                            600: '#4F3AE7',
                        }
                    }
                }
            }
        };
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="h-full antialiased text-slate-800 bg-[#F3F5FA] flex flex-col lg:flex-row">

    <!-- Mobile Top Navigation Bar -->
    <header class="lg:hidden bg-[#131427] text-white p-4 flex items-center justify-between sticky top-0 z-50 border-b border-slate-800">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 font-black text-lg">
            <span class="w-8 h-8 rounded-xl bg-gradient-to-tr from-[#5945F7] to-purple-500 text-white flex items-center justify-center shadow-md">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M16 7a4 4 0 00-8 0v1H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2v-9a2 2 0 00-2-2h-3V7zm-6 1V7a2 2 0 114 0v1h-4zm-1 6a1 1 0 112 0 3 3 0 006 0 1 1 0 112 0 5 5 0 01-10 0z"/></svg>
            </span>
            <span>ShopEase</span>
        </a>
        <button type="button" onclick="document.getElementById('admin-sidebar').classList.toggle('-translate-x-full')" class="p-2 rounded-lg bg-slate-800 text-slate-300 hover:text-white">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
    </header>

    <!-- Sidebar: Dark Navigation (Matches Exact Image Style) -->
    <aside id="admin-sidebar" class="fixed inset-y-0 left-0 z-40 w-64 bg-[#131427] text-slate-300 flex flex-col justify-between p-5 transform -translate-x-full lg:translate-x-0 transition-transform duration-200 ease-in-out lg:static lg:h-screen lg:sticky lg:top-0">
        <div class="space-y-6">
            <!-- Brand Header -->
            <div class="flex items-center justify-between px-2 pt-2">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 group">
                    <span class="w-9 h-9 rounded-xl bg-gradient-to-tr from-[#5945F7] to-purple-500 text-white flex items-center justify-center shadow-md shadow-indigo-900/40">
                        <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M16 7a4 4 0 00-8 0v1H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2v-9a2 2 0 00-2-2h-3V7zm-6 1V7a2 2 0 114 0v1h-4zm-1 6a1 1 0 112 0 3 3 0 006 0 1 1 0 112 0 5 5 0 01-10 0z"/>
                        </svg>
                    </span>
                    <span class="text-xl font-black text-white tracking-tight">ShopEase</span>
                </a>
                <span class="text-slate-500 hover:text-slate-300 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </span>
            </div>

            <!-- Sidebar Navigation Links -->
            <nav class="space-y-1.5 pt-2">
                <!-- 1. Dashboard -->
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center gap-3 px-3.5 py-3 rounded-2xl text-xs font-bold transition {{ request()->routeIs('admin.dashboard') ? 'bg-[#5945F7] text-white shadow-lg shadow-[#5945F7]/30' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span>Dashboard</span>
                </a>

                <!-- 2. Categories -->
                <a href="{{ route('admin.categories.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-3 rounded-2xl text-xs font-bold transition {{ request()->routeIs('admin.categories.*') ? 'bg-[#5945F7] text-white shadow-lg shadow-[#5945F7]/30' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.categories.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                    </svg>
                    <span>Categories</span>
                </a>

                <!-- 3. Subcategories -->
                <a href="{{ route('admin.subcategories.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-3 rounded-2xl text-xs font-bold transition {{ request()->routeIs('admin.subcategories.*') ? 'bg-[#5945F7] text-white shadow-lg shadow-[#5945F7]/30' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.subcategories.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/>
                    </svg>
                    <span>Subcategories</span>
                </a>

                <!-- 4. Products -->
                <a href="{{ route('admin.products.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-3 rounded-2xl text-xs font-bold transition {{ request()->routeIs('admin.products.*') ? 'bg-[#5945F7] text-white shadow-lg shadow-[#5945F7]/30' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.products.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    <span>Products</span>
                </a>

                <!-- 5. Orders -->
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center gap-3 px-3.5 py-3 rounded-2xl text-xs font-bold text-slate-400 hover:bg-slate-800/60 hover:text-white transition">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                    <span>Orders</span>
                </a>

                <!-- 6. Settings -->
                <a href="{{ route('profile') }}" 
                   class="flex items-center gap-3 px-3.5 py-3 rounded-2xl text-xs font-bold text-slate-400 hover:bg-slate-800/60 hover:text-white transition">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span>Settings</span>
                </a>
            </nav>
        </div>

        <!-- Sidebar Bottom: Storefront Link & Logout -->
        <div class="space-y-2 pt-4 border-t border-slate-800/80">
            <a href="{{ route('storefront.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-400 hover:text-white hover:bg-slate-800/60 transition">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                <span>View Storefront</span>
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" 
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold text-slate-400 hover:text-rose-400 hover:bg-slate-800/60 transition cursor-pointer text-left">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 min-h-screen">
        <!-- Topbar -->
        <header class="bg-white border-b border-slate-200/80 px-6 py-3.5 flex items-center justify-between sticky top-0 z-30 shadow-2xs">
            <!-- Search Input -->
            <div class="relative w-72 sm:w-80">
                <input type="text" 
                       placeholder="Search..." 
                       class="w-full pl-9 pr-4 py-2 rounded-xl bg-[#F4F6FB] border-0 text-xs font-medium text-slate-700 placeholder-slate-400 focus:bg-white focus:ring-2 focus:ring-[#5945F7] focus:outline-none transition">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
            </div>

            <!-- Admin Profile / Dropdown on Right -->
            <div class="relative group">
                <button type="button" class="flex items-center gap-2.5 p-1 rounded-xl hover:bg-slate-100 transition cursor-pointer">
                    @if(auth()->user()?->profile_url)
                        <img src="{{ auth()->user()->profile_url }}" alt="Admin" class="w-8 h-8 rounded-full object-cover">
                    @else
                        <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-[#5945F7] to-purple-600 text-white font-bold text-xs flex items-center justify-center shadow-xs">
                            {{ strtoupper(substr(auth()->user()?->name ?? 'Admin', 0, 1)) }}
                        </div>
                    @endif
                    <span class="text-xs font-bold text-slate-800 hidden sm:inline">{{ auth()->user()?->name ?? 'Admin' }}</span>
                    <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-slate-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                </button>

                <!-- Dropdown -->
                <div class="absolute right-0 top-full pt-1.5 w-48 hidden group-hover:block hover:block z-50">
                    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 p-2 text-left">
                        <div class="px-3 py-2 border-b border-slate-100 mb-1">
                            <p class="text-xs font-bold text-slate-900 truncate">{{ auth()->user()?->name ?? 'Admin' }}</p>
                            <p class="text-[10px] text-slate-400 truncate">{{ auth()->user()?->email ?? 'admin@example.com' }}</p>
                        </div>
                        <a href="{{ route('profile') }}" class="block px-3 py-1.5 rounded-xl text-xs font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition">Profile</a>
                        <a href="{{ route('storefront.index') }}" class="block px-3 py-1.5 rounded-xl text-xs font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition">View Storefront</a>
                        <form method="POST" action="{{ route('logout') }}" class="pt-1 border-t border-slate-100">
                            @csrf
                            <button type="submit" class="w-full text-left px-3 py-1.5 rounded-xl text-xs font-semibold text-rose-600 hover:bg-rose-50 transition cursor-pointer">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Flash Messages -->
        <div class="px-6 sm:px-8 pt-4">
            @if(session('success'))
                <div class="p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center justify-between shadow-2xs mb-4">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif
            @if(session('error'))
                <div class="p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold flex items-center justify-between shadow-2xs mb-4">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif
        </div>

        <!-- Main Body -->
        <main class="flex-1 p-6 sm:p-8">
            @yield('content')
        </main>
    </div>
</body>
</html>
