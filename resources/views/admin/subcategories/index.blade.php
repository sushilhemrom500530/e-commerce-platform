@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Subcategories Management</h1>
            <p class="text-xs text-slate-500 mt-1">Organize subcategories under their parent categories.</p>
        </div>
        <a href="{{ route('admin.subcategories.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-indigo-600 text-white text-sm font-bold shadow-sm hover:bg-indigo-700 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>New Subcategory</span>
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="bg-slate-50/80 text-xs uppercase font-bold text-slate-500 border-b border-slate-200">
                <tr>
                    <th class="py-3 px-4">Subcategory Name</th>
                    <th class="py-3 px-4">Parent Category</th>
                    <th class="py-3 px-4">Slug (SEO)</th>
                    <th class="py-3 px-4">Products</th>
                    <th class="py-3 px-4">Status</th>
                    <th class="py-3 px-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 font-medium">
                @forelse($subcategories as $subcategory)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-3.5 px-4 font-bold text-slate-900 flex items-center gap-3">
                            @if($subcategory->image)
                                <img src="{{ $subcategory->image_url }}" alt="{{ $subcategory->name }}" class="w-8 h-8 rounded-lg object-cover border border-slate-200">
                            @else
                                <span class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xs border border-indigo-100">
                                    {{ substr($subcategory->name, 0, 1) }}
                                </span>
                            @endif
                            <span>{{ $subcategory->name }}</span>
                        </td>
                        <td class="py-3.5 px-4">
                            <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                {{ $subcategory->category->name }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 font-mono text-xs text-indigo-600">{{ $subcategory->slug }}</td>
                        <td class="py-3.5 px-4">
                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                                {{ $subcategory->products_count }} products
                            </span>
                        </td>
                        <td class="py-3.5 px-4">
                            <span class="px-2 py-0.5 rounded-full text-[11px] font-bold {{ $subcategory->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                {{ $subcategory->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 text-right space-x-2">
                            <a href="{{ route('admin.subcategories.edit', $subcategory) }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800">Edit</a>
                            <form action="{{ route('admin.subcategories.destroy', $subcategory) }}" method="POST" class="inline-block" onsubmit="return confirm('Deleting this subcategory will cascade delete all related products. Continue?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs font-bold text-rose-600 hover:text-rose-800">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-slate-400">No subcategories found. Click "New Subcategory" to add one.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $subcategories->links() }}</div>
</div>
@endsection
