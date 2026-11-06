@extends('layouts.app')

@section('title', 'About Us - ShopEase Bangladesh')

@section('content')
<div class="space-y-16 py-4">
    <!-- Hero Banner / Story Intro -->
    <section class="relative rounded-3xl sm:rounded-[2.5rem] bg-gradient-to-br from-[#EEF2FF] via-[#F5F3FF] to-[#E0E7FF] border border-indigo-100/60 p-8 sm:p-14 overflow-hidden shadow-xs">
        <div class="max-w-3xl space-y-6 relative z-10">
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-600 text-white text-[11px] font-bold uppercase tracking-wider shadow-xs">
                Our Story & Purpose
            </span>
            <h1 class="text-3xl sm:text-5xl font-black text-slate-900 tracking-tight leading-[1.15]">
                Smarter shopping, honest pricing, and genuine products for Bangladesh.
            </h1>
            <p class="text-slate-600 text-base sm:text-lg font-medium leading-relaxed">
                Founded in Dhaka in 2024, ShopEase was born out of a simple frustration: finding authentic tech and lifestyle essentials online shouldn't feel like a gamble. We set out to build an e-commerce platform that puts trust, verified quality, and personal customer care at the center of everything we do.
            </p>
            <div class="pt-2 flex flex-wrap gap-4">
                <a href="{{ route('storefront.products') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs sm:text-sm shadow-md shadow-indigo-200 transition hover:scale-105 duration-200">
                    <span>Explore Our Products</span>
                    <span>&rarr;</span>
                </a>
                <a href="{{ route('storefront.contact') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs sm:text-sm border border-slate-200 shadow-xs transition">
                    <span>Talk to Our Team</span>
                </a>
            </div>
        </div>

        <!-- Decorative Subtle Watermark Background Icon -->
        <div class="absolute -right-12 -bottom-12 opacity-5 pointer-events-none select-none">
            <svg class="w-96 h-96 text-indigo-900" viewBox="0 0 24 24" fill="currentColor">
                <path d="M16 7a4 4 0 00-8 0v1H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2v-9a2 2 0 00-2-2h-3V7zm-6 1V7a2 2 0 114 0v1h-4zm-1 6a1 1 0 112 0 3 3 0 006 0 1 1 0 112 0 5 5 0 01-10 0z"/>
            </svg>
        </div>
    </section>

    <!-- Key Impact Metrics (Human Milestones) -->
    <section class="grid grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-6 text-center space-y-1">
            <p class="text-3xl sm:text-4xl font-black text-indigo-600">25,000+</p>
            <h4 class="text-xs font-bold text-slate-800">Happy Shoppers</h4>
            <p class="text-[11px] text-slate-400">Across 64 districts in Bangladesh</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-6 text-center space-y-1">
            <p class="text-3xl sm:text-4xl font-black text-indigo-600">100%</p>
            <h4 class="text-xs font-bold text-slate-800">Genuine Products</h4>
            <p class="text-[11px] text-slate-400">Strict zero-counterfeit guarantee</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-6 text-center space-y-1">
            <p class="text-3xl sm:text-4xl font-black text-indigo-600">24-48 hrs</p>
            <h4 class="text-xs font-bold text-slate-800">Fast Delivery</h4>
            <p class="text-[11px] text-slate-400">Direct courier dispatch from Dhaka</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-6 text-center space-y-1">
            <p class="text-3xl sm:text-4xl font-black text-indigo-600">99.4%</p>
            <h4 class="text-xs font-bold text-slate-800">Satisfaction Rate</h4>
            <p class="text-[11px] text-slate-400">Based on verified customer reviews</p>
        </div>
    </section>

    <!-- Our Core Values (Human Pillars) -->
    <section class="space-y-8">
        <div class="text-center max-w-2xl mx-auto space-y-2">
            <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">The Principles That Guide Us</h2>
            <p class="text-xs sm:text-sm text-slate-500 font-medium">Every decision we make starts with one question: how does this make shopping better for our customers?</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Pillar 1 -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-6 space-y-4 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <h3 class="font-extrabold text-base text-slate-900">Zero-Compromise Authenticity</h3>
                <p class="text-xs text-slate-500 leading-relaxed">
                    We source directly from authorized brand importers and verified manufacturer hubs. Every device and apparel unit is hand-checked by our quality inspection team before shipping.
                </p>
            </div>

            <!-- Pillar 2 -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-6 space-y-4 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="font-extrabold text-base text-slate-900">Honest & Fair Pricing</h3>
                <p class="text-xs text-slate-500 leading-relaxed">
                    No misleading discounts or inflated baseline prices. We work tirelessly to eliminate unnecessary middlemen markups and pass real savings straight to our community.
                </p>
            </div>

            <!-- Pillar 3 -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-6 space-y-4 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L3 11V5a2 2 0 012-2h10a2 2 0 012 2v3"/></svg>
                </div>
                <h3 class="font-extrabold text-base text-slate-900">Real Human Support</h3>
                <p class="text-xs text-slate-500 leading-relaxed">
                    When you call or message us, you reach a real person at our Dhaka hub who actually cares. Whether it is a delivery update or an exchange request, we resolve issues without endless ticket loops.
                </p>
            </div>
        </div>
    </section>

    <!-- Leadership & Team Spotlight -->
    <section class="space-y-8">
        <div class="text-center max-w-xl mx-auto space-y-2">
            <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Meet the People Behind ShopEase</h2>
            <p class="text-xs sm:text-sm text-slate-500 font-medium">A dedicated team based in Gulshan, Dhaka, passionate about elevating Bangladesh's e-commerce landscape.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <!-- Member 1 -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-6 text-center space-y-3">
                <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=400&auto=format&fit=crop&q=80" 
                     alt="Nusrat Jahan" 
                     class="w-20 h-20 rounded-full mx-auto object-cover ring-4 ring-indigo-50 shadow-sm">
                <div>
                    <h4 class="font-extrabold text-slate-900 text-sm">Nusrat Jahan</h4>
                    <p class="text-[11px] font-semibold text-indigo-600">Head of Operations & Care</p>
                </div>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Ensuring every customer order is packed with care and delivered smoothly across Bangladesh.
                </p>
            </div>

            <!-- Member 2 -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-6 text-center space-y-3">
                <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&auto=format&fit=crop&q=80" 
                     alt="Tanvir Hossain" 
                     class="w-20 h-20 rounded-full mx-auto object-cover ring-4 ring-indigo-50 shadow-sm">
                <div>
                    <h4 class="font-extrabold text-slate-900 text-sm">Tanvir Hossain</h4>
                    <p class="text-[11px] font-semibold text-indigo-600">Co-Founder & Product Lead</p>
                </div>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Connecting with certified tech and fashion brands to bring world-class products to Dhaka.
                </p>
            </div>

            <!-- Member 3 -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-6 text-center space-y-3">
                <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=400&auto=format&fit=crop&q=80" 
                     alt="Farhan Ahmed" 
                     class="w-20 h-20 rounded-full mx-auto object-cover ring-4 ring-indigo-50 shadow-sm">
                <div>
                    <h4 class="font-extrabold text-slate-900 text-sm">Farhan Ahmed</h4>
                    <p class="text-[11px] font-semibold text-indigo-600">Quality & Logistics Manager</p>
                </div>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Guarantees rigorous hardware testing, authentication, and ultra-fast hub dispatching.
                </p>
            </div>
        </div>
    </section>

    <!-- Bottom CTA Card -->
    <section class="bg-slate-900 text-white rounded-3xl p-8 sm:p-12 text-center space-y-6 relative overflow-hidden">
        <div class="max-w-xl mx-auto space-y-3 relative z-10">
            <h3 class="text-2xl sm:text-3xl font-black tracking-tight">Ready to experience hassle-free shopping?</h3>
            <p class="text-slate-300 text-xs sm:text-sm font-medium">Explore thousands of verified products with fast delivery and easy 7-day returns.</p>
            <div class="pt-2">
                <a href="{{ route('storefront.products') }}" class="inline-flex items-center gap-2 px-7 py-3.5 rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs sm:text-sm shadow-lg shadow-indigo-600/30 transition hover:scale-105 duration-200">
                    <span>Shop All Products</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>
    </section>
</div>
@endsection

