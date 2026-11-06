@extends('layouts.app')

@section('title', 'Shopping Cart - ' . config('app.name', 'ShopEase'))

@section('content')
<div class="space-y-6">
    <!-- Header & Breadcrumbs -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-100">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Shopping Cart</h1>
            <nav class="flex items-center gap-1.5 text-xs font-semibold text-slate-500 mt-1">
                <a href="{{ route('storefront.index') }}" class="hover:text-indigo-600 transition">Home</a>
                <span class="text-slate-400">&rsaquo;</span>
                <span class="text-slate-800 font-bold">Cart</span>
            </nav>
        </div>
        <a href="{{ route('storefront.products') }}" class="inline-flex items-center gap-2 text-xs font-bold text-indigo-600 hover:text-indigo-700 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Continue Shopping</span>
        </a>
    </div>

    <!-- Empty State -->
    <div id="empty-cart-state" class="{{ count($cart) === 0 ? '' : 'hidden' }} bg-white rounded-3xl border border-slate-200/80 p-12 text-center space-y-4 shadow-xs max-w-xl mx-auto my-8">
        <div class="w-20 h-20 rounded-3xl bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto shadow-inner">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
            </svg>
        </div>
        <div class="space-y-1">
            <h2 class="text-xl font-black text-slate-900">Your shopping cart is empty</h2>
            <p class="text-xs text-slate-500 max-w-sm mx-auto">Explore our collection of authentic products and find what you need today.</p>
        </div>
        <div class="pt-2">
            <a href="{{ route('storefront.products') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-md shadow-indigo-100 transition active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                <span>Browse All Products</span>
            </a>
        </div>
    </div>

    <!-- Cart Layout (Left: Items, Right: Order Summary) -->
    <div id="cart-content-grid" class="{{ count($cart) > 0 ? '' : 'hidden' }} grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        <!-- Left Side: Product Items List -->
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-5 sm:p-6 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                        <span>Cart Items</span>
                        <span id="cart-items-count-badge" class="px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-600 text-xs font-bold">
                            {{ count($cart) }}
                        </span>
                    </h2>
                </div>

                <!-- Products Table / List -->
                <div id="cart-items-list" class="divide-y divide-slate-100">
                    @foreach($cart as $item)
                        <div id="cart-row-{{ $item['id'] }}" class="py-4 first:pt-0 last:pb-0 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <!-- Product Info -->
                            <div class="flex items-center gap-3.5 min-w-0 flex-1">
                                <a href="{{ route('storefront.product', $item['slug'] ?: $item['id']) }}" class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-[#F8F9FD] border border-slate-200/60 p-2 shrink-0 flex items-center justify-center overflow-hidden">
                                    <img src="{{ $item['image'] ?: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=200' }}" 
                                         alt="{{ $item['name'] }}" 
                                         class="w-full h-full object-contain">
                                </a>
                                <div class="min-w-0 flex-1 space-y-1">
                                    <h3 class="text-sm font-bold text-slate-900 line-clamp-1 hover:text-indigo-600 transition">
                                        <a href="{{ route('storefront.product', $item['slug'] ?: $item['id']) }}">
                                            {{ $item['name'] }}
                                        </a>
                                    </h3>
                                    <div class="text-xs text-slate-500 font-medium">
                                        Unit Price: <span class="font-bold text-slate-800">৳{{ number_format($item['price'], 0) }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Quantity Controls & Item Total -->
                            <div class="flex items-center justify-between sm:justify-end gap-6 shrink-0">
                                <!-- Increase / Decrease Quantity -->
                                <div class="flex items-center border border-slate-200 rounded-xl bg-slate-50/80 p-0.5 shadow-2xs">
                                    <button type="button" 
                                            onclick="updateCartItemQty({{ $item['id'] }}, -1)"
                                            class="w-7 h-7 rounded-lg bg-white hover:bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-xs transition shadow-2xs cursor-pointer select-none"
                                            title="Decrease quantity">
                                        &minus;
                                    </button>
                                    <span id="qty-{{ $item['id'] }}" class="w-9 text-center text-xs font-bold text-slate-900">
                                        {{ $item['quantity'] }}
                                    </span>
                                    <button type="button" 
                                            onclick="updateCartItemQty({{ $item['id'] }}, 1)"
                                            class="w-7 h-7 rounded-lg bg-white hover:bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-xs transition shadow-2xs cursor-pointer select-none"
                                            title="Increase quantity">
                                        &#43;
                                    </button>
                                </div>

                                <!-- Item Total Amount -->
                                <div class="text-right min-w-[80px]">
                                    <span id="item-total-{{ $item['id'] }}" class="text-sm font-black text-slate-900">
                                        ৳{{ number_format($item['price'] * $item['quantity'], 0) }}
                                    </span>
                                </div>

                                <!-- Remove Button -->
                                <button type="button" 
                                        onclick="removeCartItem({{ $item['id'] }})"
                                        class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer"
                                        title="Remove product">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Right Side: Order Summary -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 space-y-5 sticky top-24">
                <h2 class="text-base font-black text-slate-900 tracking-tight pb-3 border-b border-slate-100">
                    Order Summary
                </h2>

                <div class="space-y-3 text-xs">
                    <div class="flex items-center justify-between text-slate-600">
                        <span>Subtotal</span>
                        <span id="cart-summary-subtotal" class="font-bold text-slate-900">৳{{ number_format($subtotal, 0) }}</span>
                    </div>

                    <div class="flex items-center justify-between text-slate-600">
                        <span>Estimated Shipping</span>
                        <span id="cart-summary-shipping" class="font-bold {{ $shipping == 0 ? 'text-emerald-600' : 'text-slate-900' }}">
                            {{ $shipping == 0 ? 'FREE' : '৳' . number_format($shipping, 0) }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between text-slate-600">
                        <span>Vat / Tax</span>
                        <span class="font-bold text-emerald-600">Included</span>
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-sm">
                        <span class="font-bold text-slate-900">Total Price</span>
                        <span id="cart-summary-total" class="font-black text-xl text-indigo-600">৳{{ number_format($total, 0) }}</span>
                    </div>
                </div>

                <!-- Order Action Form -->
                <form action="{{ route('cart.checkout') }}" method="POST" class="pt-2">
                    @csrf
                    <button type="submit" 
                            class="w-full py-3.5 px-5 rounded-2xl bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-extrabold text-xs uppercase tracking-wider shadow-lg shadow-indigo-200 transition active:scale-98 flex items-center justify-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        <span>Place Order Now</span>
                    </button>
                </form>

                <!-- Trust Points -->
                <div class="pt-3 border-t border-slate-100 space-y-2 text-[11px] text-slate-500 font-medium">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Cash on Delivery available nationwide</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Fast delivery within 24–48 hours</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-purple-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        <span>100% Genuine and authentic products</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function updateCartItemQty(productId, change) {
        const qtyEl = document.getElementById(`qty-${productId}`);
        if (!qtyEl) return;

        let currentQty = parseInt(qtyEl.textContent.trim(), 10) || 1;
        let newQty = currentQty + change;

        if (newQty <= 0) {
            removeCartItem(productId);
            return;
        }

        qtyEl.textContent = newQty;

        fetch('{{ route("cart.update") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ product_id: productId, quantity: newQty })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                applyCartUpdates(data, productId);
            }
        })
        .catch(err => console.error(err));
    }

    function removeCartItem(productId) {
        const row = document.getElementById(`cart-row-${productId}`);
        if (row) {
            row.style.opacity = '0.3';
        }

        fetch('{{ route("cart.remove") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ product_id: productId })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                if (row) row.remove();
                applyCartUpdates(data, productId, true);
                if (window.showToast) {
                    window.showToast('Item removed from cart', 'notice');
                }
            }
        })
        .catch(err => console.error(err));
    }

    function applyCartUpdates(data, productId, wasRemoved = false) {
        // Update item total if not removed
        if (!wasRemoved && data.cart && data.cart[productId]) {
            const item = data.cart[productId];
            const itemTotalEl = document.getElementById(`item-total-${productId}`);
            if (itemTotalEl) {
                itemTotalEl.textContent = '৳' + Number(item.price * item.quantity).toLocaleString();
            }
        }

        // Update Summary values
        const subtotalEl = document.getElementById('cart-summary-subtotal');
        const shippingEl = document.getElementById('cart-summary-shipping');
        const totalEl = document.getElementById('cart-summary-total');
        const badgeEl = document.getElementById('cart-items-count-badge');
        const navBadge = document.getElementById('navbar-cart-badge');

        if (subtotalEl) subtotalEl.textContent = '৳' + Number(data.subtotal).toLocaleString();
        if (shippingEl) {
            shippingEl.textContent = data.shipping == 0 ? 'FREE' : '৳' + Number(data.shipping).toLocaleString();
            shippingEl.className = data.shipping == 0 ? 'font-bold text-emerald-600' : 'font-bold text-slate-900';
        }
        if (totalEl) totalEl.textContent = '৳' + Number(data.total).toLocaleString();
        if (badgeEl) badgeEl.textContent = Object.keys(data.cart || {}).length;

        // Update Navbar Badge
        if (navBadge) {
            navBadge.textContent = data.total_count;
            if (data.total_count > 0) {
                navBadge.classList.remove('hidden');
            } else {
                navBadge.classList.add('hidden');
            }
        }

        // Toggle Empty State if 0 items
        if (!data.cart || Object.keys(data.cart).length === 0) {
            const emptyState = document.getElementById('empty-cart-state');
            const cartGrid = document.getElementById('cart-content-grid');
            if (emptyState) emptyState.classList.remove('hidden');
            if (cartGrid) cartGrid.classList.add('hidden');
        }
    }
</script>
@endpush
@endsection

