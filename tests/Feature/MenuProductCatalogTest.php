<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductCategory;
use Database\Seeders\MenuProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MenuProductCatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_storage_urls_are_same_origin_relative_paths(): void
    {
        $this->assertSame('/storage/products/example.jpg', Storage::disk('public')->url('products/example.jpg'));
    }

    public function test_menu_import_replaces_legacy_catalog_with_complete_menu(): void
    {
        $this->seed(MenuProductSeeder::class);

        $this->assertSame(31, Product::count());
        $this->assertSame(['Makanan', 'Minuman', 'Snack'], ProductCategory::query()->orderBy('name')->pluck('name')->all());
        $this->assertSame(0, Product::query()->whereNull('description')->orWhere('description', '')->count());
        $this->assertDatabaseMissing('products', ['slug' => 'arabika-gayo-single-origin-250g']);
        $this->assertDatabaseHas('products', [
            'slug' => 'rice-bowl-chicken-katsu',
            'price' => 11000,
            'stock_status' => 'tersedia',
        ]);
    }

    public function test_catalog_and_product_detail_render_imported_menu(): void
    {
        $this->seed(MenuProductSeeder::class);

        $this->get(route('produk'))
            ->assertOk()
            ->assertSee('Es Kopi Gula Aren')
            ->assertSee('Makanan')
            ->assertSee('Minuman')
            ->assertSee('Snack');

        $this->get(route('produk.detail', 'rice-bowl-ayam-kecap'))
            ->assertOk()
            ->assertSee('Rice Bowl Ayam Kecap')
            ->assertSee('Deskripsi Produk');
    }
}
