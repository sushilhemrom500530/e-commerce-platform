<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class StorefrontController extends Controller
{
    /**
     * Display all products organized by 5 primary categories on the home page.
     */
    public function index(Request $request): View|JsonResponse
    {
        $featuredSlugs = ['electronics', 'fashion', 'home-living', 'sports', 'books'];

        $categoriesData = Cache::remember('storefront_home_categories_v2', 3600, function () use ($featuredSlugs) {
            $categories = Category::where('is_active', true)
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

            if ($categories->count() < 5) {
                $existingIds = $categories->pluck('id')->all();
                $additional = Category::where('is_active', true)
                    ->whereNotIn('id', $existingIds)
                    ->whereHas('products', fn ($q) => $q->where('is_active', true))
                    ->with([
                        'products' => fn ($q) => $q->where('is_active', true)->with(['category', 'subcategory'])->latest()->take(4),
                        'subcategories' => fn ($q) => $q->where('is_active', true)->take(5),
                    ])
                    ->withCount('products')
                    ->take(5 - $categories->count())
                    ->get();
                $categories = $categories->concat($additional);
            }

            return $categories->map(function ($c) {
                return [
                    'id' => $c->id,
                    'name' => $c->name,
                    'slug' => $c->slug,
                    'description' => $c->description,
                    'products_count' => (int) $c->products_count,
                    'products' => $c->products->map(fn ($p) => [
                        'id' => $p->id,
                        'name' => $p->name,
                        'slug' => $p->slug,
                        'image_url' => $p->image_url,
                        'discount_percentage' => $p->discount_percentage,
                        'new_price' => (float) $p->new_price,
                        'old_price' => $p->old_price ? (float) $p->old_price : null,
                        'subcategory_name' => $p->subcategory?->name,
                    ])->all(),
                ];
            })->all();
        });

        $homeCategories = collect($categoriesData)->map(function ($c) {
            $cat = new \stdClass;
            $cat->id = $c['id'];
            $cat->name = $c['name'];
            $cat->slug = $c['slug'];
            $cat->description = $c['description'];
            $cat->products_count = $c['products_count'];
            $cat->products = collect($c['products'])->map(function ($p) {
                $prod = new \stdClass;
                $prod->id = $p['id'];
                $prod->name = $p['name'];
                $prod->slug = $p['slug'];
                $prod->image_url = $p['image_url'];
                $prod->discount_percentage = $p['discount_percentage'];
                $prod->new_price = $p['new_price'];
                $prod->old_price = $p['old_price'];
                $prod->subcategory = $p['subcategory_name'] ? (object) ['name' => $p['subcategory_name']] : null;

                return $prod;
            });

            return $cat;
        });

        if ($request->expectsJson() && ! $request->header('X-Inertia')) {
            return $this->sendResponse($homeCategories, 'Catalog retrieved successfully.');
        }

        return view('storefront.index', compact('homeCategories'));
    }

    /**
     * Display all products catalog with category filtering, sorting, and pagination.
     */
    public function products(Request $request): View|JsonResponse
    {
        $categoriesData = Cache::remember('storefront_catalog_categories_v2', 3600, function () {
            return Category::where('is_active', true)
                ->with(['subcategories' => fn ($q) => $q->where('is_active', true)->orderBy('name')])
                ->orderBy('name')
                ->get()
                ->map(fn ($c) => [
                    'id' => $c->id,
                    'name' => $c->name,
                    'slug' => $c->slug,
                    'subcategories' => $c->subcategories->map(fn ($s) => [
                        'id' => $s->id,
                        'name' => $s->name,
                        'slug' => $s->slug,
                    ])->all(),
                ])->all();
        });

        $categories = collect($categoriesData)->map(function ($c) {
            $cat = new \stdClass;
            $cat->id = $c['id'];
            $cat->name = $c['name'];
            $cat->slug = $c['slug'];
            $cat->subcategories = collect($c['subcategories'])->map(fn ($s) => (object) $s);

            return $cat;
        });

        $selectedCategory = null;
        if ($request->filled('category')) {
            $catParam = (string) $request->query('category');
            $selectedCategory = is_numeric($catParam)
                ? Category::where('id', (int) $catParam)->first()
                : Category::where('slug', $catParam)->first();
        }

        $selectedSubcategory = null;
        if ($request->filled('subcategory')) {
            $subParam = (string) $request->query('subcategory');
            $selectedSubcategory = is_numeric($subParam)
                ? Subcategory::where('id', (int) $subParam)->first()
                : Subcategory::where('slug', $subParam)->first();

            if ($selectedSubcategory && ! $selectedCategory) {
                $selectedCategory = $selectedSubcategory->category;
            }
        }

        $query = Product::with(['category', 'subcategory'])
            ->where('is_active', true);

        if ($selectedCategory) {
            $query->where('category_id', $selectedCategory->id);
        }

        if ($selectedSubcategory) {
            $query->where('subcategory_id', $selectedSubcategory->id);
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
                'selected_subcategory' => $selectedSubcategory,
            ], 'Products catalog retrieved successfully.');
        }

        return view('storefront.products', compact('products', 'categories', 'selectedCategory', 'selectedSubcategory', 'sort'));
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

        $relatedProducts = Cache::remember("related_products_{$product->id}", 3600, function () use ($product) {
            return Product::where('subcategory_id', $product->subcategory_id)
                ->where('id', '!=', $product->id)
                ->where('is_active', true)
                ->limit(4)
                ->get();
        });

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
