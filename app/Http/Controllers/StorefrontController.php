<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StorefrontController extends Controller
{
    /**
     * Display all products organized by 5 primary categories on the home page.
     */
    public function index(Request $request): View|JsonResponse
    {
        $featuredSlugs = ['electronics', 'fashion', 'home-living', 'sports', 'books'];
        $homeCategories = Category::where('is_active', true)
            ->whereIn('slug', $featuredSlugs)
            ->whereHas('products', fn ($q) => $q->where('is_active', true))
            ->with([
                'products' => fn ($q) => $q->where('is_active', true)->with(['category', 'subcategory'])->latest()->take(4),
                'subcategories' => fn ($q) => $q->where('is_active', true)->take(5),
            ])
            ->withCount('products')
            ->get()
            ->sortBy(fn ($cat) => array_search($cat->slug, $featuredSlugs))
            ->values();

        if ($homeCategories->count() < 5) {
            $existingIds = $homeCategories->pluck('id')->all();
            $additional = Category::where('is_active', true)
                ->whereNotIn('id', $existingIds)
                ->whereHas('products', fn ($q) => $q->where('is_active', true))
                ->with([
                    'products' => fn ($q) => $q->where('is_active', true)->with(['category', 'subcategory'])->latest()->take(4),
                    'subcategories' => fn ($q) => $q->where('is_active', true)->take(5),
                ])
                ->withCount('products')
                ->take(5 - $homeCategories->count())
                ->get();
            $homeCategories = $homeCategories->concat($additional);
        }

        $categories = Category::where('is_active', true)->withCount('products')->get();

        $subcategories = Subcategory::where('is_active', true)
            ->whereHas('products', fn ($q) => $q->where('is_active', true))
            ->with(['category', 'products' => fn ($q) => $q->where('is_active', true)->latest()])
            ->orderBy('name')
            ->get();

        if ($request->expectsJson() && ! $request->header('X-Inertia')) {
            return $this->sendResponse($homeCategories, 'Catalog retrieved successfully.');
        }

        return view('storefront.index', compact('homeCategories', 'categories', 'subcategories'));
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

    /**
     * Display the About Us story, values, and company overview.
     */
    public function about(): View
    {
        return view('storefront.about');
    }

    /**
     * Display the Contact Support, office details, and inquiry form.
     */
    public function contact(): View
    {
        return view('storefront.contact');
    }

    /**
     * Handle incoming contact inquiries with validation and user feedback.
     */
    public function submitContact(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:25'],
            'subject' => ['required', 'string', 'max:120'],
            'message' => ['required', 'string', 'min:10', 'max:2500'],
        ]);

        logger()->info('Storefront customer contact inquiry received:', $validated);

        return back()->with('success', 'Thank you, '.$validated['name'].'! Your message has reached our support team in Dhaka. We will respond within 2-4 business hours.');
    }
}
