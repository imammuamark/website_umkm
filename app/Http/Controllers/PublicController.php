<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\BusinessProfile;
use App\Models\ContactMessage;
use App\Models\Location;
use App\Models\Page;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\SiteSetting;
use App\Support\ArticleTableOfContents;
use App\Support\ArticleVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PublicController extends Controller
{
    /**
     * Display the Homepage.
     */
    public function home()
    {
        $profile = BusinessProfile::first();
        $featuredProducts = Product::featured()->with(['category', 'media'])->take(3)->get();
        $bestsellers = Product::bestseller()->with(['category', 'media'])->take(4)->get();
        $latestArticles = Article::published()->with(['category', 'media'])->latest('published_at')->take(3)->get();
        $primaryLocation = Location::orderBy('id')->first();
        $aboutPage = Page::published()->whereIn('slug', ['tentang-panama', 'tentang-kopi'])
            ->with('media')
            ->orderByRaw("CASE WHEN slug = 'tentang-panama' THEN 0 ELSE 1 END")
            ->first();

        $storyImage = $aboutPage?->getFirstMediaUrl('content_image', 'large')
            ?: $aboutPage?->getResolvedHeroUrl()
            ?: $latestArticles->first()?->resolvedFeaturedImageUrl('large');

        // Stats
        $stats = [
            'founded_year' => $profile ? $profile->founded_year : 2021,
            'products_count' => Product::count(),
            'locations_count' => Location::count(),
        ];

        return view('home', compact(
            'profile',
            'featuredProducts',
            'bestsellers',
            'latestArticles',
            'stats',
            'primaryLocation',
            'aboutPage',
            'storyImage',
        ));
    }

    /**
     * Display the Business Profile Page.
     */
    public function profil()
    {
        $profile = BusinessProfile::first();
        $profileStats = [
            'years' => $profile?->founded_year
                ? max(1, now()->year - (int) $profile->founded_year)
                : null,
            'products' => Product::count(),
            'locations' => Location::count(),
        ];

        return view('profil', compact('profile', 'profileStats'));
    }

    /**
     * Display the Product Catalog.
     */
    public function katalog(Request $request)
    {
        $categories = ProductCategory::whereNull('parent_id')
            ->with(['children' => fn ($query) => $query->withCount('products')])
            ->withCount('products')
            ->orderBy('name')
            ->get();

        $query = Product::query()->with(['category', 'media']);

        // Apply filters
        if ($request->filled('q')) {
            $search = mb_substr(trim((string) $request->input('q')), 0, 100);
            $query->where('name', 'like', '%'.$search.'%');
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

        if ($request->filled('min_price') && is_numeric($request->input('min_price'))) {
            $query->where('price', '>=', max(0, (float) $request->input('min_price')));
        }

        if ($request->filled('max_price') && is_numeric($request->input('max_price'))) {
            $query->where('price', '<=', max(0, (float) $request->input('max_price')));
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
                'pagination' => view('partials.pagination', compact('products'))->render(),
            ]);
        }

        return view('katalog', compact('categories', 'products'));
    }

    /**
     * Display Product Detail Page.
     */
    public function produkDetail(Request $request, string $slug)
    {
        $product = Product::with(['category', 'media'])->where('slug', $slug)->firstOrFail();

        $viewKey = 'viewed_product_'.$product->id;
        if (! $request->session()->has($viewKey)) {
            $product->increment('views_count');
            $request->session()->put($viewKey, true);
        }

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with(['category', 'media'])
            ->take(4)
            ->get();

        $whatsappNumber = SiteSetting::get('whatsapp_number', '6281234567890');
        $whatsappTemplate = SiteSetting::get('whatsapp_text_template', 'Halo Admin, saya tertarik dengan produk {product_name}');

        $whatsappText = str_replace('{product_name}', $product->name, $whatsappTemplate);
        $whatsappUrl = 'https://wa.me/'.$whatsappNumber.'?text='.urlencode($whatsappText);

        return view('produk_detail', compact('product', 'relatedProducts', 'whatsappUrl'));
    }

    /**
     * Display Blog Articles Index.
     */
    public function artikel(Request $request)
    {
        $categories = ArticleCategory::all();
        $query = Article::published()->with(['category', 'author', 'media']);

        if ($request->filled('q')) {
            $search = mb_substr(trim((string) $request->input('q')), 0, 100);
            $query->where(function ($query) use ($search): void {
                $query->where('title', 'like', '%'.$search.'%')
                    ->orWhere('content', 'like', '%'.$search.'%');
            });
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
    public function artikelDetail(string $slug, ArticleTableOfContents $tableOfContents)
    {
        $article = Article::published()->with(['category', 'author', 'media'])->where('slug', $slug)->firstOrFail();

        $relatedArticles = Article::published()
            ->where('category_id', $article->category_id)
            ->where('id', '!=', $article->id)
            ->with(['category', 'media'])
            ->take(3)
            ->get();

        $content = $tableOfContents->transform($article->content);
        $articleVideos = collect($article->video_urls ?? [])
            ->take(5)
            ->map(function (array $video): ?array {
                $resolved = ArticleVideo::resolve($video['url'] ?? null);

                return $resolved ? [...$resolved, 'title' => trim((string) ($video['title'] ?? ''))] : null;
            })
            ->filter()
            ->values();
        $articleGallery = $article->getMedia('content_images')
            ->map(fn ($image): array => [
                'url' => $image->getUrl('content'),
                'alt' => $image->getCustomProperty('alt', $article->title),
                'caption' => $image->getCustomProperty('caption', ''),
            ])
            ->concat($article->resolvedExternalImages())
            ->values();

        return view('artikel_detail', [
            'article' => $article,
            'relatedArticles' => $relatedArticles,
            'articleContent' => $content['html'],
            'tableOfContents' => $content['items'],
            'articleVideos' => $articleVideos,
            'articleGallery' => $articleGallery,
        ]);
    }

    /**
     * Display Store Locations.
     */
    public function lokasi()
    {
        $locations = Location::orderBy('name')->get();
        $mapsEmbedUrl = $this->trustedGoogleMapsEmbedUrl(
            SiteSetting::get('google_maps_embed')
        );

        return view('lokasi', compact('locations', 'mapsEmbedUrl'));
    }

    /**
     * Extract a trusted Google Maps embed URL without rendering admin-provided HTML.
     */
    private function trustedGoogleMapsEmbedUrl(?string $embed): ?string
    {
        if (blank($embed)) {
            return null;
        }

        $candidate = trim(html_entity_decode($embed, ENT_QUOTES | ENT_HTML5, 'UTF-8'));

        if (str_starts_with($candidate, '<')) {
            if (! preg_match('/\bsrc\s*=\s*["\']([^"\']+)["\']/i', $candidate, $matches)) {
                return null;
            }

            $candidate = $matches[1];
        }

        if (! filter_var($candidate, FILTER_VALIDATE_URL)) {
            return null;
        }

        $parts = parse_url($candidate);
        $host = strtolower($parts['host'] ?? '');
        $path = $parts['path'] ?? '';

        $trustedHosts = ['www.google.com', 'google.com', 'maps.google.com'];

        parse_str((string) ($parts['query'] ?? ''), $query);
        $isEmbedPath = str_starts_with($path, '/maps/embed');
        $isSafePlaceQuery = $path === '/maps'
            && ($query['output'] ?? null) === 'embed'
            && filled($query['q'] ?? null)
            && mb_strlen((string) $query['q']) <= 500;

        if (($parts['scheme'] ?? '') !== 'https'
            || ! in_array($host, $trustedHosts, true)
            || (! $isEmbedPath && ! $isSafePlaceQuery)) {
            return null;
        }

        return $candidate;
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
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'email' => ['required', 'string', 'email:rfc', 'max:150'],
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+() .-]+$/'],
            'message' => ['required', 'string', 'min:10', 'max:1000'],
            'website' => ['nullable', 'string', 'max:0'],
            'source_page' => ['nullable', Rule::in(['kontak'])],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Data yang diberikan tidak valid.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        ContactMessage::create([
            'name' => trim($validated['name']),
            'email' => mb_strtolower(trim($validated['email'])),
            'phone' => filled($validated['phone'] ?? null) ? trim($validated['phone']) : null,
            'message' => trim($validated['message']),
            'status' => 'baru',
            'source_page' => 'kontak',
        ]);

        return response()->json([
            'message' => 'Pesan Anda berhasil dikirim. Tim kami akan segera menghubungi Anda!',
        ]);
    }

    /**
     * Display a dynamic custom page.
     */
    public function customPage(string $slug)
    {
        $page = Page::published()->where('slug', $slug)->firstOrFail();

        $extraData = [];
        if ($page->template === 'locations') {
            $extraData['locations'] = Location::orderBy('name')->get();
            $extraData['mapsEmbedUrl'] = $this->trustedGoogleMapsEmbedUrl(
                SiteSetting::get('google_maps_embed')
            );
        } elseif ($page->template === 'about') {
            $profile = BusinessProfile::first();
            $extraData['profile'] = $profile;
            $extraData['profileStats'] = [
                'years' => $profile?->founded_year
                    ? max(1, now()->year - (int) $profile->founded_year)
                    : null,
                'products' => Product::count(),
                'locations' => Location::count(),
            ];
        }

        return view('halaman', array_merge(compact('page'), $extraData));
    }
}
