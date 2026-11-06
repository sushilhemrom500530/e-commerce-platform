@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto my-8">
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-8 space-y-6">
        <div class="text-center space-y-2">
            <span class="w-12 h-12 rounded-2xl bg-indigo-600 text-white inline-flex items-center justify-center font-black text-xl shadow-md shadow-indigo-200">PL</span>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Sign In to Your Account</h1>
            <p class="text-xs text-slate-500">Sign in to access your customer orders or manage admin portal</p>
        </div>

        <!-- Quick Demo Credentials Pill -->
        <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200/80 text-xs space-y-2">
            <span class="font-bold text-slate-700 block">⚡ Quick Demo Credentials:</span>
            <div class="grid grid-cols-2 gap-2">
                <button type="button" onclick="fillCreds('admin@example.com', 'Admin@123456')" class="p-2 rounded-xl bg-white border border-slate-200 hover:border-indigo-400 text-left transition">
                    <span class="font-bold text-indigo-600 block">Store Admin</span>
                    <span class="text-[11px] text-slate-500 font-mono">admin@example.com</span>
                </button>
                <button type="button" onclick="fillCreds('customer@example.com', 'Customer@123456')" class="p-2 rounded-xl bg-white border border-slate-200 hover:border-indigo-400 text-left transition">
                    <span class="font-bold text-slate-700 block">Customer</span>
                    <span class="text-[11px] text-slate-500 font-mono">customer@example.com</span>
                </button>
            </div>
        </div>

        <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none @error('email') border-rose-500 @enderror">
                @error('email')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Password</label>
                <input type="password" id="password" name="password" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none @error('password') border-rose-500 @enderror">
                @error('password')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center gap-2 cursor-pointer text-slate-600 font-medium">
                    <input type="checkbox" name="remember" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                    <span>Remember me</span>
                </label>
            </div>

            <button type="submit" class="w-full py-3 rounded-xl bg-indigo-600 text-white text-sm font-bold hover:bg-indigo-700 shadow-sm transition">
                Sign In
            </button>
        </form>

        <div class="text-center text-xs text-slate-500 pt-2 border-t border-slate-100">
            Don't have an account? 
            <a href="{{ route('register') }}" class="font-bold text-indigo-600 hover:text-indigo-700">Register as a customer</a>
        </div>
    </div>
</div>

<script>
    function fillCreds(email, password) {
        document.getElementById('email').value = email;
        document.getElementById('password').value = password;
    }
</script>
@endsection
