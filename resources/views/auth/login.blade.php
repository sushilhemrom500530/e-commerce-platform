@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto py-8 sm:py-12">
    <div class="bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-100/60 p-6 sm:p-10">
        <!-- Header -->
        <div class="text-center space-y-2 mb-8">
            <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto shadow-inner">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Welcome Back</h1>
            <p class="text-xs sm:text-sm text-slate-500">Sign in to your ShopEase account to manage orders, profile, and settings.</p>
        </div>

        <!-- Quick Demo Credentials Box -->
        <div class="mb-6 p-3.5 rounded-2xl bg-indigo-50/70 border border-indigo-100 space-y-2">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-indigo-900 uppercase tracking-wider flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-indigo-600 animate-pulse"></span>
                    Quick Demo Credentials
                </span>
                <span class="text-[10px] text-indigo-500 font-semibold">1-Click Auto Fill</span>
            </div>
            <div class="grid grid-cols-2 gap-2 pt-1">
                <button type="button" 
                        onclick="fillCredentials('admin@example.com', 'Admin@123456')" 
                        class="px-2.5 py-1.5 rounded-xl bg-white border border-indigo-200 text-indigo-700 text-[11px] font-bold hover:bg-indigo-600 hover:text-white transition shadow-2xs text-left cursor-pointer">
                    <span class="block truncate">👑 Admin Account</span>
                    <span class="text-[9px] text-slate-400 block font-normal">admin@example.com</span>
                </button>
                <button type="button" 
                        onclick="fillCredentials('customer@example.com', 'Customer@123456')" 
                        class="px-2.5 py-1.5 rounded-xl bg-white border border-indigo-200 text-indigo-700 text-[11px] font-bold hover:bg-indigo-600 hover:text-white transition shadow-2xs text-left cursor-pointer">
                    <span class="block truncate">🛍️ Customer Account</span>
                    <span class="text-[9px] text-slate-400 block font-normal">customer@example.com</span>
                </button>
            </div>
        </div>

        <!-- Login Form -->
        <form action="{{ route('login.submit') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Email Field -->
            <div class="space-y-1.5">
                <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Email Address</label>
                <div class="relative">
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required 
                           autofocus
                           placeholder="you@example.com"
                           class="w-full pl-10 pr-4 py-3 rounded-xl border {{ $errors->has('email') ? 'border-rose-300 ring-2 ring-rose-100' : 'border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100' }} text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none transition">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                    </span>
                </div>
                @error('email')
                    <p class="text-xs font-semibold text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Field -->
            <div class="space-y-1.5">
                <div class="flex items-center justify-between">
                    <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Password</label>
                </div>
                <div class="relative">
                    <input type="password" 
                           id="password" 
                           name="password" 
                           required 
                           placeholder="••••••••"
                           class="w-full pl-10 pr-10 py-3 rounded-xl border {{ $errors->has('password') ? 'border-rose-300 ring-2 ring-rose-100' : 'border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100' }} text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none transition">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </span>
                    <button type="button" 
                            onclick="togglePasswordVisibility()" 
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer">
                        <svg id="eye-icon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </button>
                </div>
                @error('password')
                    <p class="text-xs font-semibold text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between pt-1">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="w-4 h-4 text-indigo-600 rounded border-slate-300 focus:ring-indigo-500">
                    <span class="text-xs font-medium text-slate-600">Remember me</span>
                </label>
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                    class="w-full py-3.5 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm shadow-md shadow-indigo-200 transition hover:scale-[1.01] active:scale-[0.99] cursor-pointer flex items-center justify-center gap-2">
                <span>Sign In</span>
                <span>&rarr;</span>
            </button>
        </form>

        <!-- Footer / Register Link -->
        <div class="mt-8 pt-6 border-t border-slate-100 text-center">
            <p class="text-xs text-slate-500 font-medium">
                Don't have an account? 
                <a href="{{ route('register') }}" class="font-bold text-indigo-600 hover:text-indigo-700 underline">Create one now</a>
            </p>
        </div>
    </div>
</div>

<script>
    function fillCredentials(email, password) {
        document.getElementById('email').value = email;
        document.getElementById('password').value = password;
    }

    function togglePasswordVisibility() {
        const pass = document.getElementById('password');
        if (pass.type === 'password') {
            pass.type = 'text';
        } else {
            pass.type = 'password';
        }
    }
</script>
@endsection
