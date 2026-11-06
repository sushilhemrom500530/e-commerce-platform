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
        $search = $request->input('search');

        $subcategories = Subcategory::where('is_active', true)
            ->whereHas('products', function ($q) use ($search) {
                $q->where('is_active', true);
                if ($search) {
                    $q->where(fn ($sub) => $sub->where('name', 'like', "%{$search}%")->orWhere('description', 'like', "%{$search}%"));
                }
            })
            ->with(['category', 'products' => function ($q) use ($search) {
                $q->where('is_active', true);
                if ($search) {
                    $q->where(fn ($sub) => $sub->where('name', 'like', "%{$search}%")->orWhere('description', 'like', "%{$search}%"));
                }
                $q->latest();
            }])
            ->orderBy('name')
            ->get();

        $categories = Category::where('is_active', true)->withCount('products')->get();

        if ($request->expectsJson() && ! $request->header('X-Inertia')) {
            return $this->sendResponse($subcategories, 'Catalog retrieved successfully.');
        }

        return view('storefront.index', compact('subcategories', 'categories', 'search'));
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
