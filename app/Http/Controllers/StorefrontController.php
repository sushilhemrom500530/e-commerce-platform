<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StorefrontController extends Controller
{
    /**
     * Display all products organized by their respective subcategories.
     */
    public function index(Request $request): View|JsonResponse
    {
        $subcategories = Subcategory::where('is_active', true)
            ->whereHas('products', fn ($q) => $q->where('is_active', true))
            ->with(['category', 'products' => fn ($q) => $q->where('is_active', true)->latest()])
            ->orderBy('name')
            ->get();

        $categories = Category::where('is_active', true)->withCount('products')->get();

        if ($request->expectsJson() && ! $request->header('X-Inertia')) {
            return $this->sendResponse($subcategories, 'Catalog retrieved successfully.');
        }

        return view('storefront.index', compact('subcategories', 'categories'));
    }

    /**
     * Display all products catalog with category filtering, sorting, and pagination.
     */
    public function products(Request $request): View|JsonResponse
    {
        $categories = Category::where('is_active', true)
            ->withCount('products')
            ->orderBy('name')
            ->get();

        $selectedCategory = null;
        if ($request->filled('category')) {
            $catParam = (string) $request->query('category');
            $selectedCategory = is_numeric($catParam)
                ? Category::where('id', (int) $catParam)->first()
                : Category::where('slug', $catParam)->first();
        }

        $query = Product::with(['category', 'subcategory'])
            ->where('is_active', true);

        if ($selectedCategory) {
            $query->where('category_id', $selectedCategory->id);
        }

        if ($request->filled('search')) {
            $search = trim((string) $request->query('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $sort = $request->query('sort', 'newest');
        match ($sort) {
            'price_asc' => $query->orderBy('new_price', 'asc'),
            'price_desc' => $query->orderBy('new_price', 'desc'),
            'name_asc' => $query->orderBy('name', 'asc'),
            'name_desc' => $query->orderBy('name', 'desc'),
            default => $query->latest('id'),
        };

        $products = $query->paginate(9)->withQueryString();

        if ($request->expectsJson() && ! $request->header('X-Inertia')) {
            return $this->sendResponse([
                'products' => $products,
                'categories' => $categories,
                'selected_category' => $selectedCategory,
            ], 'Products catalog retrieved successfully.');
        }

        return view('storefront.products', compact('products', 'categories', 'selectedCategory', 'sort'));
    }

    /**
     * Display individual product details using its SEO-friendly slug.
     */
    public function show(Request $request, Product $product): View|JsonResponse
    {
        $product->load(['category', 'subcategory']);

        if ($request->expectsJson() && ! $request->header('X-Inertia')) {
            return $this->sendResponse($product, 'Product retrieved successfully.');
        }

        $relatedProducts = Product::where('subcategory_id', $product->subcategory_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->limit(4)
            ->get();

        return view('storefront.show', compact('product', 'relatedProducts'));
    }
}
