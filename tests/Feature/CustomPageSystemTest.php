<?php

namespace Tests\Feature;

use App\Models\BusinessProfile;
use App\Models\MenuItem;
use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomPageSystemTest extends TestCase
{
    use RefreshDatabase;

    public function test_custom_page_renders_when_published(): void
    {
        $page = Page::create([
            'title' => 'Tanya Jawab Kopi',
            'slug' => 'tanya-jawab',
            'content' => '<p>Konten halaman tanya jawab kopi spesialti.</p>',
            'status' => 'published',
            'is_in_navigation' => true,
            'sort_order' => 5,
        ]);

        $response = $this->get(route('page.detail', 'tanya-jawab'));
        $response->assertStatus(200);
        $response->assertSee('Tanya Jawab Kopi');
        $response->assertSee('Konten halaman tanya jawab kopi spesialti.');
    }

    public function test_custom_page_returns_404_when_draft(): void
    {
        $page = Page::create([
            'title' => 'Halaman Rahasia',
            'slug' => 'halaman-rahasia',
            'content' => '<p>Konten rahasia.</p>',
            'status' => 'draft',
        ]);

        $response = $this->get(route('page.detail', 'halaman-rahasia'));
        $response->assertStatus(404);
    }

    public function test_xss_protection_removes_dangerous_scripts_from_content(): void
    {
        $page = Page::create([
            'title' => 'Halaman Aman',
            'slug' => 'halaman-aman',
            'content' => '<p>Aman</p><script>alert("xss")</script><iframe src="hack.com"></iframe><a href="javascript:alert(1)">Link</a>',
            'status' => 'published',
        ]);

        $this->assertStringNotContainsString('script', $page->fresh()->content);
        $this->assertStringNotContainsString('iframe', $page->fresh()->content);
        $this->assertStringNotContainsString('javascript:', $page->fresh()->content);
        $this->assertStringContainsString('<p>Aman</p>', $page->fresh()->content);
    }

    public function test_old_routes_redirect_to_new_dynamic_pages(): void
    {
        $response1 = $this->get('/profil');
        $response1->assertStatus(302);
        $response1->assertRedirect(route('page.detail', 'tentang-kopi'));

        $response2 = $this->get('/lokasi');
        $response2->assertStatus(302);
        $response2->assertRedirect(route('page.detail', 'lokasi'));
    }

    public function test_menu_navigation_resolves_and_renders_correctly(): void
    {
        $aboutPage = Page::create([
            'title' => 'Tentang Kopi Kami',
            'slug' => 'tentang-kopi',
            'content' => '<p>Konten tentang kopi.</p>',
            'status' => 'published',
        ]);

        $menuItem = MenuItem::create([
            'label' => 'Tentang Kami Baru',
            'type' => 'page',
            'page_id' => $aboutPage->id,
            'sort_order' => 10,
            'is_active' => true,
        ]);

        $this->assertEquals(route('page.detail', 'tentang-kopi'), $menuItem->getUrl());

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Tentang Kami Baru');
    }

    public function test_custom_page_renders_widgets(): void
    {
        $page = Page::create([
            'title' => 'Page With Widgets',
            'slug' => 'page-with-widgets',
            'content' => '<p>Page content.</p>',
            'status' => 'published',
            'widgets' => [
                [
                    'type' => 'social',
                    'platform' => 'instagram',
                    'url' => 'https://instagram.com/dummy',
                ],
                [
                    'type' => 'social',
                    'platform' => 'tiktok',
                    'url' => 'https://tiktok.com/@dummy',
                ],
                [
                    'type' => 'youtube',
                    'title' => 'Video Profil Kami',
                    'video_url' => 'https://www.youtube.com/watch?v=BY31NstC_t4',
                ],
            ],
        ]);

        $response = $this->get(route('page.detail', 'page-with-widgets'));
        $response->assertStatus(200);
        $response->assertSee('Connect with Panama Corner');
        $response->assertSee('Video Profil Kami');
        $response->assertSee('https://instagram.com/dummy');
        $response->assertSee('https://www.youtube-nocookie.com/embed/BY31NstC_t4');
    }

    public function test_about_template_is_selected_by_template_and_has_no_hardcoded_image(): void
    {
        BusinessProfile::create([
            'business_name' => 'Usaha Contoh',
            'description' => 'Deskripsi usaha.',
            'vision' => 'Visi usaha.',
            'mission' => 'Misi usaha.',
            'founded_year' => 2022,
        ]);

        Page::create([
            'title' => 'Profil Perusahaan',
            'slug' => 'profil-perusahaan',
            'template' => 'about',
            'eyebrow' => 'Tentang Usaha',
            'subtitle' => 'Cerita yang dikelola dari CMS.',
            'content' => '<p>Konten profil dinamis.</p>',
            'status' => 'published',
        ]);

        $response = $this->get(route('page.detail', 'profil-perusahaan'));

        $response->assertOk()
            ->assertSee('Tentang Usaha')
            ->assertSee('Cerita yang dikelola dari CMS.')
            ->assertSee('Konten profil dinamis.')
            ->assertDontSee('about-heritage.jpg', false)
            ->assertDontSee('panama-roastery-hero.png', false);
    }

    public function test_page_hero_supports_safe_external_url_and_credit(): void
    {
        Page::create([
            'title' => 'Tentang Usaha',
            'slug' => 'tentang-usaha-url',
            'template' => 'standard',
            'content' => '<p>Konten usaha.</p>',
            'status' => 'published',
            'hero_source' => 'url',
            'hero_image_url' => 'https://images.unsplash.com/photo-example?q=80',
            'hero_alt' => 'Aktivitas usaha',
            'hero_credit' => 'Foto dari Unsplash',
            'hero_credit_url' => 'https://unsplash.com/',
        ]);

        $response = $this->get(route('page.detail', 'tentang-usaha-url'));

        $response->assertOk()
            ->assertSee('https://images.unsplash.com/photo-example?q=80', false)
            ->assertSee('Aktivitas usaha')
            ->assertSee('Foto dari Unsplash');
    }

    public function test_page_hero_rejects_unsafe_external_url(): void
    {
        $page = Page::create([
            'title' => 'Halaman URL Tidak Aman',
            'slug' => 'unsafe-hero-url',
            'content' => '<p>Konten aman.</p>',
            'status' => 'published',
            'hero_source' => 'url',
            'hero_image_url' => 'javascript:alert(1)',
        ]);

        $this->assertNull($page->getResolvedHeroUrl());

        $this->get(route('page.detail', 'unsafe-hero-url'))
            ->assertOk()
            ->assertDontSee('javascript:alert(1)', false);
    }

    public function test_system_pages_receive_distinct_default_hero_images(): void
    {
        $this->seed();

        $aboutCoffee = Page::where('slug', 'tentang-kopi')->firstOrFail();
        $aboutBusiness = Page::where('slug', 'tentang-panama')->firstOrFail();
        $locations = Page::where('slug', 'lokasi')->firstOrFail();

        $this->assertSame('url', $aboutCoffee->hero_source);
        $this->assertSame('url', $aboutBusiness->hero_source);
        $this->assertSame('url', $locations->hero_source);
        $this->assertNotSame($aboutCoffee->hero_image_url, $aboutBusiness->hero_image_url);
        $this->assertNotSame($locations->hero_image_url, $aboutBusiness->hero_image_url);
        $this->assertNotSame($locations->hero_image_url, $aboutCoffee->hero_image_url);
    }
}
