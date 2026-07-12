<?php

namespace App\Http\Controllers;

use App\Models\DigitalMenuAccessPoint;
use App\Models\DigitalMenuSetting;
use App\Models\ProductCategory;
use App\Models\SiteSetting;
use App\Support\DigitalMenuCache;
use App\Support\DigitalMenuQr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DigitalMenuController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'category' => ['nullable', 'string', 'max:120', 'regex:/^[a-z0-9-]+$/'],
            't' => ['nullable', 'string', 'size:16', 'regex:/^[a-z0-9]+$/'],
        ]);

        $settings = DigitalMenuSetting::current();
        abort_unless($settings->is_enabled, 404);

        $accessPoint = null;
        if ($request->filled('t')) {
            $accessPoint = DigitalMenuAccessPoint::where('public_id', $request->string('t'))
                ->where('is_active', true)
                ->first();

            if ($accessPoint) {
                $accessPoint->increment('scans_count');
                $accessPoint->forceFill(['last_scanned_at' => now()])->saveQuietly();
            }
        }

        // Only cache scalar identifiers. Serializing Eloquent collections can leave
        // incomplete objects when a cache entry is read by another PHP worker.
        $categoryIds = Cache::remember(DigitalMenuCache::KEY, now()->addMinutes(10), function (): array {
            return ProductCategory::query()
                ->where('is_menu_visible', true)
                ->whereHas('products', fn ($query) => $query->where('is_menu_visible', true))
                ->orderBy('menu_sort_order')
                ->orderBy('name')
                ->pluck('id')
                ->map(fn ($id): int => (int) $id)
                ->all();
        });

        $categories = ProductCategory::query()
            ->whereIn('id', $categoryIds)
            ->with(['products' => fn ($query) => $query
                ->where('is_menu_visible', true)
                ->with('media')
                ->orderBy('menu_sort_order')
                ->orderBy('name')])
            ->orderBy('menu_sort_order')
            ->orderBy('name')
            ->get();

        $selectedCategory = $request->string('category')->toString()
            ?: $accessPoint?->category?->slug;

        $primary = $settings->use_theme_colors ? SiteSetting::get('theme_primary_color', '#0F766E') : $settings->primary_color;
        $accent = $settings->use_theme_colors ? SiteSetting::get('theme_secondary_color', '#F59E0B') : $settings->accent_color;

        return response()->view('digital-menu.index', compact('settings', 'categories', 'selectedCategory', 'accessPoint', 'primary', 'accent'))
            ->header('X-Robots-Tag', $settings->allow_indexing ? 'index, follow' : 'noindex, follow')
            ->header('Cache-Control', 'public, max-age=60, stale-while-revalidate=300');
    }

    public function qr(DigitalMenuAccessPoint $accessPoint, string $format, DigitalMenuQr $generator)
    {
        abort_unless($accessPoint->is_active, 404);

        abort_unless(in_array($format, ['png', 'pdf'], true), 404);
        $content = $format === 'pdf'
            ? $generator->printablePdf($accessPoint->publicUrl(), $accessPoint->label)
            : $generator->printablePng($accessPoint->publicUrl(), $accessPoint->label);
        $filename = 'menu-'.str($accessPoint->label)->slug().'.'.$format;

        return response($content, 200, [
            'Content-Type' => $format === 'pdf' ? 'application/pdf' : 'image/png',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            'Cache-Control' => 'private, no-store',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
