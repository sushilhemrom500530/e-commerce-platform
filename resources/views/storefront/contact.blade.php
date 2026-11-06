@extends('layouts.app')

@section('title', 'Contact Us - ShopEase Support & Office')

@section('content')
<div class="space-y-14 py-4">
    <!-- Header & Breadcrumbs -->
    <div class="text-center max-w-2xl mx-auto space-y-3">
        <nav class="flex items-center justify-center gap-1.5 text-xs font-semibold text-slate-500">
            <a href="{{ route('storefront.index') }}" class="hover:text-indigo-600 transition">Home</a>
            <span class="text-slate-400">&rsaquo;</span>
            <span class="text-slate-800 font-bold">Contact</span>
        </nav>
        <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">We're Here to Help</h1>
        <p class="text-xs sm:text-sm text-slate-500 font-medium leading-relaxed">
            Have questions about an order, warranty claim, or partnership? Reach out directly to our dedicated customer support team based in Dhaka.
        </p>
    </div>

    <!-- Contact Info Cards -->
    <section class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Office Location -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-6 space-y-3 hover:shadow-md transition">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <h3 class="font-extrabold text-slate-900 text-base">Visit Our Office</h3>
            <p class="text-xs text-slate-500 leading-relaxed">
                Level 5, Concord Tower, Road 11, Block D,<br>
                Gulshan-2, Dhaka-1212, Bangladesh
            </p>
            <div class="pt-2 text-[11px] text-slate-400 font-medium">
                Sat – Thu: 9:00 AM – 8:00 PM<br>
                Friday: 2:00 PM – 8:00 PM
            </div>
        </div>

        <!-- Phone & Hotline -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-6 space-y-3 hover:shadow-md transition">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
            </div>
            <h3 class="font-extrabold text-slate-900 text-base">Call Support</h3>
            <p class="text-xs text-slate-500 leading-relaxed">
                Hotline: <a href="tel:+8801711234567" class="text-indigo-600 font-bold hover:underline">+880 1711-234567</a><br>
                Support: <a href="tel:+8801811987654" class="text-slate-700 font-semibold hover:underline">+880 1811-987654</a>
            </p>
            <div class="pt-2 text-[11px] text-slate-400 font-medium">
                Live agents ready to help 7 days a week
            </div>
        </div>

        <!-- Email & Inquiries -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-6 space-y-3 hover:shadow-md transition">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
            <h3 class="font-extrabold text-slate-900 text-base">Email Directly</h3>
            <p class="text-xs text-slate-500 leading-relaxed">
                Help & Orders: <a href="mailto:support@shopease.com.bd" class="text-indigo-600 font-bold hover:underline">support@shopease.com.bd</a><br>
                Business: <a href="mailto:partners@shopease.com.bd" class="text-slate-700 font-semibold hover:underline">partners@shopease.com.bd</a>
            </p>
            <div class="pt-2 text-[11px] text-slate-400 font-medium">
                Guaranteed response within 2-4 business hours
            </div>
        </div>
    </section>

    <!-- Interactive Contact Form & Trust Sideboard -->
    <section class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <!-- Form Area (7 Cols) -->
        <div class="lg:col-span-7 bg-white rounded-3xl border border-slate-100 shadow-xs p-6 sm:p-10 space-y-6">
            <div>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Send Us a Direct Message</h2>
                <p class="text-xs text-slate-500 mt-1">Fill out the quick form below and a representative will get back to you promptly.</p>
            </div>

            <form action="{{ route('storefront.contact.submit') }}" method="POST" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Full Name -->
                    <div class="space-y-1.5">
                        <label for="contact-name" class="block text-xs font-bold text-slate-700">Full Name <span class="text-rose-500">*</span></label>
                        <input type="text" id="contact-name" name="name" value="{{ old('name') }}" required 
                               placeholder="e.g. Tanvir Ahmed" 
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:outline-none transition @error('name') border-rose-300 ring-1 ring-rose-300 @enderror">
                        @error('name')
                            <p class="text-[11px] text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Address -->
                    <div class="space-y-1.5">
                        <label for="contact-email" class="block text-xs font-bold text-slate-700">Email Address <span class="text-rose-500">*</span></label>
                        <input type="email" id="contact-email" name="email" value="{{ old('email') }}" required 
                               placeholder="tanvir@example.com" 
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:outline-none transition @error('email') border-rose-300 ring-1 ring-rose-300 @enderror">
                        @error('email')
                            <p class="text-[11px] text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Phone Number -->
                    <div class="space-y-1.5">
                        <label for="contact-phone" class="block text-xs font-bold text-slate-700">Phone Number (Optional)</label>
                        <input type="text" id="contact-phone" name="phone" value="{{ old('phone') }}" 
                               placeholder="+880 17XXXXXXXXX" 
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:outline-none transition @error('phone') border-rose-300 ring-1 ring-rose-300 @enderror">
                        @error('phone')
                            <p class="text-[11px] text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Subject Topic -->
                    <div class="space-y-1.5">
                        <label for="contact-subject" class="block text-xs font-bold text-slate-700">Subject / Inquiry Type <span class="text-rose-500">*</span></label>
                        <select id="contact-subject" name="subject" required 
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:outline-none transition cursor-pointer @error('subject') border-rose-300 ring-1 ring-rose-300 @enderror">
                            <option value="">Select a topic...</option>
                            <option value="Order Status & Delivery Tracking" {{ old('subject') === 'Order Status & Delivery Tracking' ? 'selected' : '' }}>Order Status & Delivery Tracking</option>
                            <option value="Product Specifications & Stock" {{ old('subject') === 'Product Specifications & Stock' ? 'selected' : '' }}>Product Specifications & Stock</option>
                            <option value="Return or Warranty Claim" {{ old('subject') === 'Return or Warranty Claim' ? 'selected' : '' }}>Return or Warranty Claim</option>
                            <option value="Corporate / Wholesale Inquiry" {{ old('subject') === 'Corporate / Wholesale Inquiry' ? 'selected' : '' }}>Corporate / Wholesale Inquiry</option>
                            <option value="General Feedback" {{ old('subject') === 'General Feedback' ? 'selected' : '' }}>General Feedback</option>
                        </select>
                        @error('subject')
                            <p class="text-[11px] text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Message -->
                <div class="space-y-1.5">
                    <label for="contact-message" class="block text-xs font-bold text-slate-700">How can we help you? <span class="text-rose-500">*</span></label>
                    <textarea id="contact-message" name="message" rows="5" required 
                              placeholder="Please provide details about your inquiry, order number (if applicable), or any questions you have..." 
                              class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:outline-none transition @error('message') border-rose-300 ring-1 ring-rose-300 @enderror">{{ old('message') }}</textarea>
                    @error('message')
                        <p class="text-[11px] text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3.5 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs sm:text-sm shadow-md shadow-indigo-200 transition hover:scale-105 duration-200 cursor-pointer">
                        <span>Send Message</span>
                        <span>&rarr;</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- FAQs & Quick Answers (5 Cols) -->
        <div class="lg:col-span-5 space-y-6">
            <div class="bg-indigo-50/60 rounded-3xl border border-indigo-100 p-6 sm:p-8 space-y-6">
                <div>
                    <h3 class="text-lg font-black text-slate-900 tracking-tight">Frequently Asked Questions</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Quick answers to common questions about our services.</p>
                </div>

                <div class="space-y-4">
                    <div class="bg-white rounded-2xl p-4 border border-indigo-100/80 shadow-xs space-y-1.5">
                        <h4 class="font-bold text-xs text-slate-900">How fast is shipping across Bangladesh?</h4>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Orders within Dhaka are delivered within 24 hours. Nationwide delivery to other divisions takes 48 to 72 hours via our courier network.
                        </p>
                    </div>

                    <div class="bg-white rounded-2xl p-4 border border-indigo-100/80 shadow-xs space-y-1.5">
                        <h4 class="font-bold text-xs text-slate-900">What payment methods can I use?</h4>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            We accept Cash on Delivery (COD) across all 64 districts, as well as bKash, Nagad, Visa, Mastercard, and American Express.
                        </p>
                    </div>

                    <div class="bg-white rounded-2xl p-4 border border-indigo-100/80 shadow-xs space-y-1.5">
                        <h4 class="font-bold text-xs text-slate-900">What is your 7-day return policy?</h4>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            If any item arrives defective or different from the description, simply notify our team within 7 days for a hassle-free exchange or full refund.
                        </p>
                    </div>

                    <div class="bg-white rounded-2xl p-4 border border-indigo-100/80 shadow-xs space-y-1.5">
                        <h4 class="font-bold text-xs text-slate-900">Are warranty claims handled locally?</h4>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Yes! Official brand warranties are serviced through authorized service centers in Dhaka and Chittagong.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
