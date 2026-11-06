@extends('layouts.app')

@section('title', 'Create Account - ShopEase')

@section('content')
<div class="max-w-md mx-auto py-8 sm:py-12">
    <div class="bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-100/60 p-6 sm:p-10">
        <!-- Header -->
        <div class="text-center space-y-2 mb-8">
            <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto shadow-inner">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Create Account</h1>
            <p class="text-xs sm:text-sm text-slate-500">Join ShopEase to discover the best deals and fastest shopping experience.</p>
        </div>

        <!-- Registration Form -->
        <form action="{{ route('register.submit') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Name Field -->
            <div class="space-y-1.5">
                <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Full Name</label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       value="{{ old('name') }}" 
                       required 
                       autofocus
                       placeholder="e.g. John Doe"
                       class="w-full px-4 py-3 rounded-xl border {{ $errors->has('name') ? 'border-rose-300 ring-2 ring-rose-100' : 'border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100' }} text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none transition">
                @error('name')
                    <p class="text-xs font-semibold text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email Field -->
            <div class="space-y-1.5">
                <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Email Address</label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       required 
                       placeholder="you@example.com"
                       class="w-full px-4 py-3 rounded-xl border {{ $errors->has('email') ? 'border-rose-300 ring-2 ring-rose-100' : 'border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100' }} text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none transition">
                @error('email')
                    <p class="text-xs font-semibold text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone Number Field -->
            <div class="space-y-1.5">
                <label for="phone_number" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Phone Number (Optional)</label>
                <input type="text" 
                       id="phone_number" 
                       name="phone_number" 
                       value="{{ old('phone_number') }}" 
                       placeholder="017XXXXXXXX"
                       class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none transition">
                @error('phone_number')
                    <p class="text-xs font-semibold text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Field -->
            <div class="space-y-1.5">
                <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Password</label>
                <input type="password" 
                       id="password" 
                       name="password" 
                       required 
                       placeholder="At least 6 characters"
                       class="w-full px-4 py-3 rounded-xl border {{ $errors->has('password') ? 'border-rose-300 ring-2 ring-rose-100' : 'border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100' }} text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none transition">
                @error('password')
                    <p class="text-xs font-semibold text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Confirmation Field -->
            <div class="space-y-1.5">
                <label for="password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Confirm Password</label>
                <input type="password" 
                       id="password_confirmation" 
                       name="password_confirmation" 
                       required 
                       placeholder="Repeat password"
                       class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none transition">
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                    class="w-full mt-2 py-3.5 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm shadow-md shadow-indigo-200 transition hover:scale-[1.01] active:scale-[0.99] cursor-pointer flex items-center justify-center gap-2">
                <span>Create Account</span>
                <span>&rarr;</span>
            </button>
        </form>

        <!-- Footer / Login Link -->
        <div class="mt-8 pt-6 border-t border-slate-100 text-center">
            <p class="text-xs text-slate-500 font-medium">
                Already have an account? 
                <a href="{{ route('login') }}" class="font-bold text-indigo-600 hover:text-indigo-700 underline">Sign in</a>
            </p>
        </div>
    </div>
</div>
@endsection

