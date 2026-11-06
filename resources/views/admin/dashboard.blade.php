@extends('layouts.admin')

@section('content')
<div class="space-y-6 max-w-7xl">
    <!-- Header -->
    <div>
        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Dashboard</h1>
        <p class="text-xs sm:text-sm text-slate-400 font-medium mt-0.5">Overview of your store</p>
    </div>

    <!-- 4 Stats Cards Grid (Exact Reference Image Style) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- 1. Total Products -->
        <a href="{{ route('admin.products.index') }}" class="group bg-white rounded-3xl p-6 border border-slate-100 shadow-xs hover:shadow-md transition block">
            <div class="w-12 h-12 rounded-2xl bg-purple-50 text-[#5945F7] flex items-center justify-center mb-4 group-hover:scale-105 transition">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M16 7a4 4 0 00-8 0v1H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2v-9a2 2 0 00-2-2h-3V7zm-6 1V7a2 2 0 114 0v1h-4zm-1 6a1 1 0 112 0 3 3 0 006 0 1 1 0 112 0 5 5 0 01-10 0z"/>
                </svg>
            </div>
            <p class="text-xs font-bold text-slate-500">Total Products</p>
            <p class="text-3xl font-black text-slate-900 tracking-tight mt-1">{{ $totalProducts }}</p>
        </a>

        <!-- 2. Total Categories -->
        <a href="{{ route('admin.categories.index') }}" class="group bg-white rounded-3xl p-6 border border-slate-100 shadow-xs hover:shadow-md transition block">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-4 group-hover:scale-105 transition">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                    <rect x="3" y="3" width="7" height="7" rx="2"/>
                    <rect x="14" y="3" width="7" height="7" rx="2"/>
                    <rect x="3" y="14" width="7" height="7" rx="2"/>
                    <rect x="14" y="14" width="7" height="7" rx="2"/>
                </svg>
            </div>
            <p class="text-xs font-bold text-slate-500">Total Categories</p>
            <p class="text-3xl font-black text-slate-900 tracking-tight mt-1">{{ $totalCategories }}</p>
        </a>

        <!-- 3. Total Orders -->
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-xs hover:shadow-md transition">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
            </div>
            <p class="text-xs font-bold text-slate-500">Total Orders</p>
            <p class="text-3xl font-black text-slate-900 tracking-tight mt-1">{{ number_format($totalOrders) }}</p>
        </div>

        <!-- 4. Total Users -->
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-xs hover:shadow-md transition">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
            <p class="text-xs font-bold text-slate-500">Total Users</p>
            <p class="text-3xl font-black text-slate-900 tracking-tight mt-1">{{ number_format($totalUsers) }}</p>
        </div>
    </div>

    <!-- Bottom Section: Sales Overview Chart + Recent Orders -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Sales Overview Chart (Left ~60-65%) -->
        <div class="lg:col-span-8 bg-white rounded-3xl p-6 border border-slate-100 shadow-xs space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-base font-black text-slate-900 tracking-tight">Sales Overview</h2>
                <div class="relative">
                    <button type="button" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition cursor-pointer">
                        <span>Last 30 Days</span>
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                </div>
            </div>

            <!-- SVG Line & Area Chart Matching Exact Reference Design -->
            <div class="pt-4">
                <div class="flex">
                    <!-- Y-Axis Labels -->
                    <div class="flex flex-col justify-between text-[11px] font-bold text-slate-400 pr-3 pb-3 select-none h-48">
                        <span>1.5k</span>
                        <span>1k</span>
                        <span>500</span>
                        <span>0</span>
                    </div>

                    <!-- Chart Canvas -->
                    <div class="flex-1 relative h-48">
                        <!-- Horizontal Grid Lines -->
                        <div class="absolute inset-0 flex flex-col justify-between pointer-events-none">
                            <div class="border-b border-dashed border-slate-100 w-full"></div>
                            <div class="border-b border-dashed border-slate-100 w-full"></div>
                            <div class="border-b border-dashed border-slate-100 w-full"></div>
                            <div class="border-b border-slate-100 w-full"></div>
                        </div>

                        <!-- SVG Curve & Gradient -->
                        <svg class="w-full h-full overflow-visible" viewBox="0 0 500 160" preserveAspectRatio="none">
                            <defs>
                                <linearGradient id="salesGrad" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#5945F7" stop-opacity="0.25"/>
                                    <stop offset="100%" stop-color="#5945F7" stop-opacity="0.0"/>
                                </linearGradient>
                            </defs>

                            <!-- Area Gradient Fill -->
                            <polygon points="10,120 70,105 130,100 200,115 270,80 340,55 410,75 480,25 480,160 10,160" 
                                     fill="url(#salesGrad)" />

                            <!-- Main Purple Line -->
                            <polyline points="10,120 70,105 130,100 200,115 270,80 340,55 410,75 480,25" 
                                      fill="none" 
                                      stroke="#5945F7" 
                                      stroke-width="3" 
                                      stroke-linecap="round" 
                                      stroke-linejoin="round" />

                            <!-- Interactive Circular Data Points (Matching Reference Image) -->
                            <circle cx="10" cy="120" r="4.5" fill="#5945F7" stroke="#ffffff" stroke-width="2"/>
                            <circle cx="70" cy="105" r="4.5" fill="#5945F7" stroke="#ffffff" stroke-width="2"/>
                            <circle cx="130" cy="100" r="4.5" fill="#5945F7" stroke="#ffffff" stroke-width="2"/>
                            <circle cx="200" cy="115" r="4.5" fill="#5945F7" stroke="#ffffff" stroke-width="2"/>
                            <circle cx="270" cy="80" r="4.5" fill="#5945F7" stroke="#ffffff" stroke-width="2"/>
                            <circle cx="340" cy="55" r="4.5" fill="#5945F7" stroke="#ffffff" stroke-width="2"/>
                            <circle cx="410" cy="75" r="4.5" fill="#5945F7" stroke="#ffffff" stroke-width="2"/>
                            <circle cx="480" cy="25" r="5" fill="#5945F7" stroke="#ffffff" stroke-width="2"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders (Right ~35-40%) -->
        <div class="lg:col-span-4 bg-white rounded-3xl p-6 border border-slate-100 shadow-xs space-y-4">
            <h2 class="text-base font-black text-slate-900 tracking-tight">Recent Orders</h2>

            <div class="space-y-3.5">
                @foreach($recentOrders as $order)
                    <div class="flex items-center justify-between p-2 rounded-2xl hover:bg-slate-50/70 transition">
                        <div class="flex items-center gap-3">
                            <img src="{{ $order['image'] }}" alt="Order Product" class="w-10 h-10 rounded-xl object-cover bg-slate-100 flex-shrink-0">
                            <div>
                                <h3 class="font-bold text-xs text-slate-900">{{ $order['order_no'] }}</h3>
                                <p class="text-[11px] text-slate-400 font-semibold mt-0.5">৳{{ number_format($order['amount']) }}</p>
                            </div>
                        </div>

                        <!-- Status Badge -->
                        <span class="px-3 py-1 rounded-full text-[11px] font-bold border {{ $order['status_class'] }}">
                            {{ $order['status'] }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
