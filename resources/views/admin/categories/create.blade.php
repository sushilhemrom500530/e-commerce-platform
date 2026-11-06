@extends('layouts.admin')

@section('title', 'Add Category - ShopEase Admin')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Create New Category</h1>
            <p class="text-xs text-slate-500 mt-1">Add a primary category for your products.</p>
        </div>
        <a href="{{ route('admin.categories.index') }}" class="text-xs font-bold text-slate-600 hover:text-slate-900">Cancel</a>
    </div>

    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs space-y-5">
        @csrf

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Category Name *</label>
            <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Electronics, Fashion, Grocery" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none @error('name') border-rose-500 @enderror" required>
            @error('name')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Description</label>
            <textarea name="description" rows="3" placeholder="Brief summary of what this category contains..." class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">{{ old('description') }}</textarea>
            @error('description')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Category Icon / Image</label>
            <input type="file" name="image" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition border border-slate-200 rounded-xl p-1">
            <p class="text-[11px] text-slate-400 mt-1">Upload a category badge or icon (PNG, JPG, SVG, WebP up to 2MB).</p>
            @error('image')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="pt-2 flex items-center justify-end gap-3">
            <a href="{{ route('admin.categories.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-300 text-xs font-bold text-slate-700 hover:bg-slate-50">Cancel</a>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 text-white text-xs font-bold hover:bg-indigo-700 shadow-sm">Save Category</button>
        </div>
    </form>
</div>
@endsection
