@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Create New Subcategory</h1>
            <p class="text-xs text-slate-500 mt-1">Assign a new subcategory to a parent category.</p>
        </div>
        <a href="{{ route('admin.subcategories.index') }}" class="text-xs font-bold text-slate-600 hover:text-slate-900">Cancel</a>
    </div>

    <form action="{{ route('admin.subcategories.store') }}" method="POST" class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs space-y-5">
        @csrf

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Parent Category *</label>
            <select name="category_id" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none @error('category_id') border-rose-500 @enderror" required>
                <option value="">Select Parent Category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Subcategory Name *</label>
            <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Mobile Phone, Laptop, Men's Clothing" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none @error('name') border-rose-500 @enderror" required>
            @error('name')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Description</label>
            <textarea name="description" rows="3" placeholder="Optional details about this subcategory..." class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">{{ old('description') }}</textarea>
            @error('description')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="pt-2 flex items-center justify-end gap-3">
            <a href="{{ route('admin.subcategories.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-300 text-xs font-bold text-slate-700 hover:bg-slate-50">Cancel</a>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 text-white text-xs font-bold hover:bg-indigo-700 shadow-sm">Save Subcategory</button>
        </div>
    </form>
</div>
@endsection
