@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Products Management</h1>
            <p class="text-xs text-slate-500 mt-1">Manage product catalog, pricing, inventory, and categories.</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-indigo-600 text-white text-sm font-bold shadow-sm hover:bg-indigo-700 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Add Product</span>
        </a>
    </div>

    <!-- Filters -->
    <form method="GET" action="{{ route('admin.products.index') }}" class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex flex-wrap gap-3 items-center">
        <div class="flex-1 min-w-[200px]">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products by title..." class="w-full px-4 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none">
        </div>
        <div class="w-48">
            <select name="category_id" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none" onchange="this.form.submit()">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="px-4 py-2 rounded-xl bg-slate-900 text-white text-xs font-bold hover:bg-slate-800">Filter</button>
        @if(request()->hasAny(['search', 'category_id']))
            <a href="{{ route('admin.products.index') }}" class="px-3 py-2 rounded-xl border border-slate-200 text-slate-500 text-xs font-semibold hover:bg-slate-50">Clear</a>
        @endif
    </form>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="bg-slate-50/80 text-xs uppercase font-bold text-slate-500 border-b border-slate-200">
                <tr>
                    <th class="py-3 px-4">Product</th>
                    <th class="py-3 px-4">Category</th>
                    <th class="py-3 px-4">Subcategory</th>
                    <th class="py-3 px-4">Old Price</th>
                    <th class="py-3 px-4">New Price</th>
                    <th class="py-3 px-4">Stock</th>
                    <th class="py-3 px-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 font-medium">
                @forelse($products as $product)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-3.5 px-4 font-bold text-slate-900 flex items-center gap-3">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-10 h-10 rounded-xl object-cover border border-slate-200">
                            <div>
                                <a href="{{ route('storefront.product', $product->slug) }}" class="hover:text-indigo-600 transition block truncate max-w-xs">
                                    {{ $product->name }}
                                </a>
                                <span class="font-mono text-[10px] text-slate-400 font-normal">/product/{{ $product->slug }}</span>
                            </div>
                        </td>
                        <td class="py-3.5 px-4">
                            <span class="px-2 py-0.5 rounded-lg text-xs font-bold bg-indigo-50 text-indigo-700">
                                {{ $product->category->name }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4">
                            <span class="px-2 py-0.5 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700">
                                {{ $product->subcategory->name }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 text-xs text-slate-400">
                            {{ $product->old_price ? '৳'.number_format($product->old_price, 2) : '—' }}
                        </td>
                        <td class="py-3.5 px-4 font-bold text-slate-900">
                            ৳{{ number_format($product->new_price, 2) }}
                        </td>
                        <td class="py-3.5 px-4">
                            <span class="text-xs font-semibold {{ $product->stock > 0 ? 'text-emerald-700' : 'text-rose-600' }}">
                                {{ $product->stock }} in stock
                            </span>
                        </td>
                        <td class="py-3.5 px-4 text-right space-x-2">
                            <a href="{{ route('storefront.product', $product->slug) }}" target="_blank" class="text-xs font-bold text-slate-500 hover:text-slate-800">View</a>
                            <a href="{{ route('admin.products.edit', $product) }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800">Edit</a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this product?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs font-bold text-rose-600 hover:text-rose-800">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-slate-400">No products found. Click "Add Product" to create one.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $products->links() }}</div>
</div>
@endsection
