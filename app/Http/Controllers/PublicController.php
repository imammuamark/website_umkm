<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\BusinessProfile;
use App\Models\ContactMessage;
use App\Models\Location;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PublicController extends Controller
{
    /**
     * Display the Homepage.
     */
    public function home()
    {
        $profile = BusinessProfile::first();
        $featuredProducts = Product::featured()->take(3)->get();
        $bestsellers = Product::bestseller()->take(4)->get();
        $latestArticles = Article::published()->latest('published_at')->take(3)->get();
        
        // Stats
        $stats = [
            'founded_year' => $profile ? $profile->founded_year : 2021,
            'products_count' => Product::count(),
            'locations_count' => Location::count(),
        ];

        return view('home', compact('profile', 'featuredProducts', 'bestsellers', 'latestArticles', 'stats'));
    }

    /**
     * Display the Business Profile Page.
     */
    public function profil()
    {
        $profile = BusinessProfile::first();
        return view('profil', compact('profile'));
    }

    /**
     * Display the Product Catalog.
     */
    public function katalog(Request $request)
    {
        $categories = ProductCategory::whereNull('parent_id')->with('children')->get();
        
        $query = Product::query()->with('category');

        // Apply filters
        if ($request->filled('q')) {
            $query->where('name', 'like', '%' . $request->input('q') . '%');
        }

        if ($request->filled('category')) {
            $categorySlug = $request->input('category');
            $category = ProductCategory::where('slug', $categorySlug)->first();
            if ($category) {
                // Include child categories if it's a parent
                $categoryIds = $category->children()->pluck('id')->push($category->id);
                $query->whereIn('category_id', $categoryIds);
            }
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->input('min_price'));
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->input('max_price'));
        }

        // Sorting
        $sort = $request->input('sort', 'latest');
        if ($sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } elseif ($sort === 'popular') {
            $query->orderBy('views_count', 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(9)->withQueryString();

        // If AJAX request, return JSON for async rendering
        if ($request->ajax()) {
            return response()->json([
                'html' => view('partials.product_grid', compact('products'))->render(),
                'pagination' => view('partials.pagination', compact('products'))->render()
            ]);
        }

        return view('katalog', compact('categories', 'products'));
    }

    /**
     * Display Product Detail Page.
     */
    public function produkDetail($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        
        // Increment views count safely
        $product->increment('views_count');

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        $whatsappNumber = SiteSetting::get('whatsapp_number', '6281234567890');
        $whatsappTemplate = SiteSetting::get('whatsapp_text_template', 'Halo Admin, saya tertarik dengan produk {product_name}');
        
        $whatsappText = str_replace('{product_name}', $product->name, $whatsappTemplate);
        $whatsappUrl = 'https://wa.me/' . $whatsappNumber . '?text=' . urlencode($whatsappText);

        return view('produk_detail', compact('product', 'relatedProducts', 'whatsappUrl'));
    }

    /**
     * Display Blog Articles Index.
     */
    public function artikel(Request $request)
    {
        $categories = ArticleCategory::all();
        $query = Article::published()->with(['category', 'author']);

        if ($request->filled('q')) {
            $query->where('title', 'like', '%' . $request->input('q') . '%')
                ->orWhere('content', 'like', '%' . $request->input('q') . '%');
        }

        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->input('category'));
            });
        }

        $articles = $query->latest('published_at')->paginate(6)->withQueryString();

        return view('artikel', compact('categories', 'articles'));
    }

    /**
     * Display Article Detail Page.
     */
    public function artikelDetail($slug)
    {
        $article = Article::published()->where('slug', $slug)->firstOrFail();

        $relatedArticles = Article::published()
            ->where('category_id', $article->category_id)
            ->where('id', '!=', $article->id)
            ->take(3)
            ->get();

        return view('artikel_detail', compact('article', 'relatedArticles'));
    }

    /**
     * Display Store Locations.
     */
    public function lokasi()
    {
        $locations = Location::all();
        $mapsEmbed = SiteSetting::get('google_maps_embed');
        return view('lokasi', compact('locations', 'mapsEmbed'));
    }

    /**
     * Display Contact Page.
     */
    public function kontak()
    {
        return view('kontak');
    }

    /**
     * Submit Contact Message (Lead Capture).
     */
    public function submitKontak(Request $request)
    {
        // Simple honeypot protection: if 'website' field is filled, reject as spam
        if ($request->filled('website')) {
            return response()->json(['message' => 'Spam detected.'], 422);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        ContactMessage::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'message' => $request->input('message'),
            'status' => 'baru',
            'source_page' => $request->input('source_page', 'kontak'),
        ]);

        return response()->json([
            'message' => 'Pesan Anda berhasil dikirim. Tim kami akan segera menghubungi Anda!'
        ]);
    }
}
