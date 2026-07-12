<?php

namespace Tests\Feature;

use App\Models\DigitalMenuAccessPoint;
use App\Models\DigitalMenuSetting;
use App\Models\Product;
use App\Models\User;
use App\Support\DigitalMenuCache;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class DigitalMenuTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
    }

    public function test_public_digital_menu_renders_visible_products_and_security_headers(): void
    {
        $product = Product::where('is_menu_visible', true)->firstOrFail();

        $this->get(route('digital-menu.index'))
            ->assertOk()
            ->assertSee('Menu Panama Corner')
            ->assertSee($product->name)
            ->assertSee('Mode seimbang')
            ->assertSee('Mode ringkas')
            ->assertHeader('X-Robots-Tag', 'noindex, follow')
            ->assertHeader('X-Content-Type-Options', 'nosniff');
    }

    public function test_hidden_product_is_not_exposed_and_disabled_menu_returns_not_found(): void
    {
        $product = Product::firstOrFail();
        $product->update(['is_menu_visible' => false]);

        $this->get(route('digital-menu.index'))->assertDontSee($product->name);

        DigitalMenuSetting::current()->update(['is_enabled' => false]);
        $this->get(route('digital-menu.index'))->assertNotFound();
    }

    public function test_access_point_tracks_scan_and_qr_download_requires_permission(): void
    {
        $point = DigitalMenuAccessPoint::firstOrFail();

        $this->get(route('digital-menu.index', ['t' => $point->public_id]))->assertOk();
        $this->assertSame(1, $point->fresh()->scans_count);

        $this->get(route('admin.digital-menu.qr', [$point, 'png']))->assertRedirect('/login');

        $admin = User::where('email', 'admin@panamacorner.com')->firstOrFail();
        $png = $this->actingAs($admin)->get(route('admin.digital-menu.qr', [$point, 'png']))
            ->assertOk()
            ->assertHeader('Content-Type', 'image/png');
        $this->assertStringStartsWith("\x89PNG", $png->getContent());

        $pdf = $this->actingAs($admin)->get(route('admin.digital-menu.qr', [$point, 'pdf']))
            ->assertOk()
            ->assertHeader('Content-Type', 'application/pdf');
        $this->assertStringStartsWith('%PDF-1.4', $pdf->getContent());
    }

    public function test_admin_workspace_loads_and_unsafe_cta_is_never_returned(): void
    {
        $admin = User::where('email', 'admin@panamacorner.com')->firstOrFail();
        $this->actingAs($admin)->get('/admin/digital-menu')
            ->assertOk()
            ->assertSee('Digital Menu Workspace')
            ->assertSee('Preview Menu')
            ->assertSee('File QR Siap Cetak');

        $settings = DigitalMenuSetting::current();
        $settings->cta_url = 'javascript:alert(1)';
        $this->assertNull($settings->safeCtaUrl());
    }

    public function test_product_updates_invalidate_menu_cache(): void
    {
        Cache::put(DigitalMenuCache::KEY, 'stale', 60);
        Product::firstOrFail()->update(['menu_sort_order' => 9]);

        $this->assertFalse(Cache::has(DigitalMenuCache::KEY));
    }

    public function test_menu_cache_contains_only_portable_scalar_identifiers(): void
    {
        $this->get(route('digital-menu.index'))->assertOk();

        $cached = Cache::get(DigitalMenuCache::KEY);
        $this->assertIsArray($cached);
        $this->assertContainsOnly('int', $cached);
    }
}
