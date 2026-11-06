@extends('layouts.admin')

@section('title', 'Add Product - ShopEase Admin')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Add New Product</h1>
            <p class="text-xs text-slate-500 mt-1">Assign category, subcategory, prices, and upload product photo.</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="text-xs font-bold text-slate-600 hover:text-slate-900">Cancel</a>
    </div>

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs space-y-6">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <!-- Category Dropdown -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Category *</label>
                <select id="category_id" name="category_id" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none @error('category_id') border-rose-500 @enderror" required>
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Dependent Subcategory Dropdown -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                    Subcategory * <span class="text-[10px] text-indigo-600 lowercase font-normal">(updates dynamically)</span>
                </label>
                <select id="subcategory_id" name="subcategory_id" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none @error('subcategory_id') border-rose-500 @enderror" required>
                    <option value="">Select Subcategory</option>
                    @if(old('category_id'))
                        @php
                            $selectedCategory = $categories->firstWhere('id', old('category_id'));
                        @endphp
                        @if($selectedCategory)
                            @foreach($selectedCategory->subcategories as $sub)
                                <option value="{{ $sub->id }}" {{ old('subcategory_id') == $sub->id ? 'selected' : '' }}>
                                    {{ $sub->name }}
                                </option>
                            @endforeach
                        @endif
                    @endif
                </select>
                @error('subcategory_id')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Product Name *</label>
            <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Apple iPhone 16 128GB" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none @error('name') border-rose-500 @enderror" required>
            @error('name')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Old Price (Regular)</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 text-sm">৳</span>
                    <input type="number" step="0.01" name="old_price" value="{{ old('old_price') }}" placeholder="120000" class="w-full pl-8 pr-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none @error('old_price') border-rose-500 @enderror">
                </div>
                @error('old_price')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">New Price (Selling) *</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 text-sm">৳</span>
                    <input type="number" step="0.01" name="new_price" value="{{ old('new_price') }}" placeholder="110000" class="w-full pl-8 pr-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none @error('new_price') border-rose-500 @enderror" required>
                </div>
                @error('new_price')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Stock Units</label>
                <input type="number" name="stock" value="{{ old('stock', 10) }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                @error('stock')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Product Description *</label>
            <textarea name="description" rows="4" placeholder="Detailed product specifications, features, warranty, and overview..." class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none @error('description') border-rose-500 @enderror" required>{{ old('description') }}</textarea>
            @error('description')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <!-- 1. Product Thumbnail Image (Main Image) -->
        <div class="p-5 bg-slate-50/80 rounded-2xl border border-slate-200/80 space-y-3">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-800 mb-1">
                    Product Thumbnail * <span class="text-indigo-600 font-semibold normal-case">(Main Display Image)</span>
                </label>
                <p class="text-[11px] text-slate-500 mb-2">This is the primary image shown in catalog lists, category cards, and shopping feeds.</p>
                <input type="file" id="image_input" name="image" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 transition border border-slate-200 rounded-xl p-1 bg-white" required>
            </div>
            @error('image')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
            @error('thumbnail')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
            <div id="image_preview_box" class="hidden">
                <p class="text-[11px] font-bold text-slate-600 mb-1">Thumbnail Preview:</p>
                <img id="image_preview" src="" class="w-24 h-24 rounded-2xl object-cover border border-slate-200 shadow-xs">
            </div>
        </div>

        <!-- 2. Featured Images (Gallery Photos) -->
        <div class="p-5 bg-slate-50/80 rounded-2xl border border-slate-200/80 space-y-3">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-800 mb-1">
                    Featured Images <span class="text-indigo-600 font-semibold normal-case">(Product Gallery & Features)</span>
                </label>
                <p class="text-[11px] text-slate-500 mb-2">Upload multiple angles, close-up details, or color variant photos (select one or multiple files).</p>
                <input type="file" id="featured_images_input" name="featured_images[]" multiple accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-800 file:text-white hover:file:bg-slate-900 transition border border-slate-200 rounded-xl p-1 bg-white">
            </div>
            @error('featured_images')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
            <div id="featured_preview_box" class="hidden">
                <p class="text-[11px] font-bold text-slate-600 mb-1.5">Featured Gallery Previews:</p>
                <div id="featured_previews" class="flex flex-wrap gap-2.5"></div>
            </div>
        </div>

        <div class="pt-2 flex items-center justify-end gap-3">
            <a href="{{ route('admin.products.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-300 text-xs font-bold text-slate-700 hover:bg-slate-50">Cancel</a>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 text-white text-xs font-bold hover:bg-indigo-700 shadow-sm">Save Product</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const categorySelect = document.getElementById('category_id');
        const subcategorySelect = document.getElementById('subcategory_id');
        const oldSubcategoryId = "{{ old('subcategory_id') }}";

        const categoryMap = @json($categories->mapWithKeys(fn ($cat) => [
            $cat->id => $cat->subcategories->map(fn ($sub) => ['id' => $sub->id, 'name' => $sub->name])
        ]));

        function renderSubcategories(categoryId, selectedId = null) {
            subcategorySelect.innerHTML = '';

            if (!categoryId) {
                const opt = document.createElement('option');
                opt.value = '';
                opt.textContent = 'Select Category First';
                subcategorySelect.appendChild(opt);
                return;
            }

            const placeholder = document.createElement('option');
            placeholder.value = '';
            placeholder.textContent = 'Select Subcategory';
            subcategorySelect.appendChild(placeholder);

            const subs = categoryMap[categoryId] || [];
            if (subs.length > 0) {
                subs.forEach(sub => {
                    const option = document.createElement('option');
                    option.value = sub.id;
                    option.textContent = sub.name;
                    if (selectedId && String(sub.id) === String(selectedId)) {
                        option.selected = true;
                    }
                    subcategorySelect.appendChild(option);
                });
            } else {
                fetch(`/admin/categories/${categoryId}/subcategories`)
                    .then(response => response.json())
                    .then(subcategories => {
                        if (subcategories && subcategories.length > 0) {
                            subcategorySelect.innerHTML = '<option value="">Select Subcategory</option>';
                            subcategories.forEach(sub => {
                                const option = document.createElement('option');
                                option.value = sub.id;
                                option.textContent = sub.name;
                                if (selectedId && String(sub.id) === String(selectedId)) {
                                    option.selected = true;
                                }
                                subcategorySelect.appendChild(option);
                            });
                        } else {
                            placeholder.textContent = 'No subcategories available';
                        }
                    })
                    .catch(() => {
                        placeholder.textContent = 'No subcategories available';
                    });
            }
        }

        categorySelect.addEventListener('change', function() {
            renderSubcategories(this.value);
        });

        if (categorySelect.value && subcategorySelect.options.length <= 1) {
            renderSubcategories(categorySelect.value, oldSubcategoryId);
        }

        // Image Preview
        const imageInput = document.getElementById('image_input');
        const previewBox = document.getElementById('image_preview_box');
        const previewImg = document.getElementById('image_preview');

        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewBox.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        });

        // Featured Gallery Images Preview
        const featuredInput = document.getElementById('featured_images_input');
        const featuredBox = document.getElementById('featured_preview_box');
        const featuredContainer = document.getElementById('featured_previews');

        featuredInput.addEventListener('change', function() {
            featuredContainer.innerHTML = '';
            if (this.files && this.files.length > 0) {
                featuredBox.classList.remove('hidden');
                Array.from(this.files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'w-16 h-16 rounded-xl object-cover border border-slate-200 shadow-2xs';
                        featuredContainer.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                });
            } else {
                featuredBox.classList.add('hidden');
            }
        });
    });
</script>
@endpush
@endsection
