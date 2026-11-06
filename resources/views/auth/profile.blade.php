@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 py-6">
    <!-- Profile Hero Card -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 sm:p-8 flex flex-col sm:flex-row items-center sm:items-start gap-6">
        <!-- Avatar Icon -->
        <div class="relative">
            @if($user->profile_url)
                <img src="{{ $user->profile_url }}" alt="{{ $user->name }}" class="w-24 h-24 rounded-2xl object-cover ring-4 ring-indigo-50 shadow-md">
            @else
                <div class="w-24 h-24 rounded-2xl bg-gradient-to-tr from-indigo-600 to-purple-600 text-white font-black text-3xl flex items-center justify-center shadow-lg shadow-indigo-200">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            @endif
            @if($user->isAdmin())
                <span class="absolute -bottom-2 -right-2 px-2 py-0.5 rounded-full bg-purple-600 text-white text-[10px] font-black uppercase tracking-wider shadow">
                    Admin
                </span>
            @endif
        </div>

        <!-- User Information -->
        <div class="flex-1 text-center sm:text-left space-y-2">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">{{ $user->name }}</h1>
                    <p class="text-xs sm:text-sm text-slate-500 font-medium">{{ $user->email }}</p>
                </div>
                <div class="flex items-center justify-center sm:justify-start gap-2">
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $user->isAdmin() ? 'bg-purple-100 text-purple-700' : 'bg-indigo-50 text-indigo-700' }}">
                        {{ ucfirst($user->role ?? 'User') }}
                    </span>
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        Active
                    </span>
                </div>
            </div>

            <div class="pt-3 flex flex-wrap items-center justify-center sm:justify-start gap-4 text-xs text-slate-500">
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>Member since {{ $user->created_at ? $user->created_at->format('M Y') : 'Recent' }}</span>
                </span>
                @if($user->phone_number)
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <span>{{ $user->phone_number }}</span>
                    </span>
                @endif
            </div>

            <!-- Quick Action Links -->
            <div class="pt-4 flex flex-wrap items-center justify-center sm:justify-start gap-2.5">
                @if($user->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold transition shadow-xs">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                        <span>Open Admin Panel</span>
                    </a>
                @endif
                <a href="{{ route('storefront.products') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-indigo-50 text-indigo-700 hover:bg-indigo-100 text-xs font-bold transition">
                    <span>Browse Catalog</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-100 text-xs font-bold transition cursor-pointer">
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Profile Form -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 sm:p-8 space-y-6">
        <div>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight">Personal Information</h2>
            <p class="text-xs text-slate-500 mt-0.5">Update your contact information and password credentials.</p>
        </div>

        <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Name -->
                <div class="space-y-1.5">
                    <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Full Name</label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $user->name) }}" 
                           required 
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 text-sm font-medium text-slate-800 focus:outline-none transition">
                    @error('name')
                        <p class="text-xs font-semibold text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email (Read Only) -->
                <div class="space-y-1.5">
                    <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Email Address</label>
                    <input type="email" 
                           id="email" 
                           value="{{ $user->email }}" 
                           disabled 
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm font-medium text-slate-500 cursor-not-allowed">
                    <p class="text-[10px] text-slate-400">Email address cannot be modified directly.</p>
                </div>

                <!-- Phone Number -->
                <div class="space-y-1.5">
                    <label for="phone_number" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Phone Number</label>
                    <input type="text" 
                           id="phone_number" 
                           name="phone_number" 
                           value="{{ old('phone_number', $user->phone_number) }}" 
                           placeholder="017XXXXXXXX"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 text-sm font-medium text-slate-800 focus:outline-none transition">
                    @error('phone_number')
                        <p class="text-xs font-semibold text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Address -->
                <div class="space-y-1.5">
                    <label for="address" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Delivery Address</label>
                    <input type="text" 
                           id="address" 
                           name="address" 
                           value="{{ old('address', $user->address) }}" 
                           placeholder="House, Street, City"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 text-sm font-medium text-slate-800 focus:outline-none transition">
                    @error('address')
                        <p class="text-xs font-semibold text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Optional Change Password Section -->
            <div class="pt-6 border-t border-slate-100 space-y-4">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Change Password (Optional)</h3>
                    <p class="text-xs text-slate-500">Leave blank if you do not want to change your current password.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label for="current_password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Current Password</label>
                        <input type="password" 
                               id="current_password" 
                               name="current_password" 
                               placeholder="••••••••"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 text-sm font-medium text-slate-800 focus:outline-none transition">
                        @error('current_password')
                            <p class="text-xs font-semibold text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label for="new_password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">New Password</label>
                        <input type="password" 
                               id="new_password" 
                               name="new_password" 
                               placeholder="Minimum 6 characters"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 text-sm font-medium text-slate-800 focus:outline-none transition">
                        @error('new_password')
                            <p class="text-xs font-semibold text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1.5 sm:col-span-2">
                        <label for="new_password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Confirm New Password</label>
                        <input type="password" 
                               id="new_password_confirmation" 
                               name="new_password_confirmation" 
                               placeholder="Repeat new password"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 text-sm font-medium text-slate-800 focus:outline-none transition">
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex items-center justify-end gap-3 pt-4">
                <button type="submit" 
                        class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-200 transition cursor-pointer">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

