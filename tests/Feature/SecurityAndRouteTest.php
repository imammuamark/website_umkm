<?php

namespace Tests\Feature;

use App\Filament\Pages\Auth\Login;
use App\Models\BusinessProfile;
use App\Models\MenuItem;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SecurityAndRouteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed basic dependencies for routes to function
        $this->artisan('db:seed');
    }

    /**
     * Test that all public routes render successfully.
     */
    public function test_public_routes_return_successful_response(): void
    {
        $routes = ['/', '/page/tentang-kopi', '/produk', '/artikel', '/page/lokasi', '/kontak'];

        foreach ($routes as $route) {
            $response = $this->get($route);
            $response->assertStatus(200);
            $response->assertSee('rel="icon"', false);

            // Check that security headers middleware works
            $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
            $response->assertHeader('X-Content-Type-Options', 'nosniff');
            $response->assertHeader('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=(), usb=()');
            $this->assertStringContainsString("object-src 'none'", (string) $response->headers->get('Content-Security-Policy'));
            $this->assertStringContainsString("worker-src 'self' blob:", (string) $response->headers->get('Content-Security-Policy'));
        }
    }

    /**
     * Test that the contact message submission works with validation.
     */
    public function test_contact_form_submits_successfully_with_valid_data(): void
    {
        $response = $this->postJson(route('kontak.submit'), [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '0812345678',
            'message' => 'Ini adalah pesan tes.',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['message']);

        $this->assertDatabaseHas('contact_messages', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);
    }

    /**
     * Test honeypot anti-spam protection.
     */
    public function test_contact_form_rejects_spam_submissions_via_honeypot(): void
    {
        $response = $this->postJson(route('kontak.submit'), [
            'name' => 'Spam Bot',
            'email' => 'bot@spam.com',
            'phone' => '123456',
            'message' => 'Spam content',
            'website' => 'http://spambot.com', // Honeypot filled
        ]);

        $response->assertStatus(422);
    }

    public function test_contact_form_rejects_tampered_source_and_invalid_phone(): void
    {
        $this->postJson(route('kontak.submit'), [
            'name' => 'Attacker Test',
            'email' => 'attacker@example.com',
            'phone' => '<script>alert(1)</script>',
            'message' => 'Pesan ini cukup panjang untuk validasi.',
            'source_page' => 'admin',
        ])->assertStatus(422);

        $this->assertDatabaseMissing('contact_messages', ['email' => 'attacker@example.com']);
    }

    public function test_custom_menu_url_rejects_executable_and_protocol_relative_urls(): void
    {
        $menu = new MenuItem(['type' => 'custom', 'url' => 'javascript:alert(1)']);
        $this->assertSame('#', $menu->getUrl());

        $menu->url = '//attacker.example/phishing';
        $this->assertSame('#', $menu->getUrl());

        $menu->url = 'https://example.com/catalog';
        $this->assertSame('https://example.com/catalog', $menu->getUrl());
    }

    public function test_posting_directly_to_admin_login_fallback_is_not_available(): void
    {
        $this->post('/admin/login')->assertStatus(405);
    }

    /**
     * Test that admin authentication and dashboard page rendering works cleanly.
     */
    public function test_admin_profile_page_loads_for_authenticated_admin(): void
    {
        $admin = User::where('email', 'admin@panamacorner.com')->first();
        $this->assertNotNull($admin);

        // Act as admin and visit Filament dashboard custom page MyProfile
        $response = $this->actingAs($admin)
            ->get('/admin/my-profile');

        $response->assertStatus(200);
        $response->assertSee('Profil Saya');
        $response->assertSee('Proyek Kewirausahaan Kelompok 1');
        $response->assertSee('Universitas UP45 Yogyakarta');
        $response->assertSee('rel="icon"', false);
    }

    public function test_admin_article_editor_loads_with_media_workspace(): void
    {
        $admin = User::where('email', 'admin@panamacorner.com')->firstOrFail();

        $this->actingAs($admin)
            ->get('/admin/articles/create')
            ->assertOk()
            ->assertSee('Ruang Kerja Konten')
            ->assertSee('Media Artikel');
    }

    public function test_admin_login_displays_academic_attribution(): void
    {
        $this->get('/admin/login')
            ->assertOk()
            ->assertSee('Proyek Kewirausahaan Kelompok 1')
            ->assertSee('Universitas UP45 Yogyakarta')
            ->assertDontSee('Digunakan untuk keperluan akademik')
            ->assertDontSee('Protected administrative area');
    }

    public function test_active_admin_can_authenticate_through_filament_login(): void
    {
        Livewire::test(Login::class)
            ->fillForm([
                'email' => 'admin@panamacorner.com',
                'password' => 'PanamaAdmin2026!',
            ])
            ->call('authenticate')
            ->assertHasNoFormErrors()
            ->assertRedirect('/admin');

        $this->assertAuthenticated();
    }

    public function test_location_page_rejects_untrusted_map_embed_html(): void
    {
        SiteSetting::set(
            'google_maps_embed',
            '<iframe src="https://evil.example/collect"></iframe><script>alert(1)</script>'
        );

        $response = $this->get(route('page.detail', 'lokasi'));

        $response->assertOk()
            ->assertDontSee('evil.example', false)
            ->assertSee('Peta sedang diperbarui');
    }

    public function test_location_page_accepts_trusted_google_maps_embed_url(): void
    {
        SiteSetting::set(
            'google_maps_embed',
            '<iframe src="https://www.google.com/maps/embed?pb=test"></iframe>'
        );

        $this->get(route('page.detail', 'lokasi'))
            ->assertOk()
            ->assertSee('https://www.google.com/maps/embed?pb=test', false)
            ->assertSee('title="Peta lokasi Panama Corner"', false);
    }

    public function test_profile_page_handles_partial_legal_document_data(): void
    {
        $profile = BusinessProfile::firstOrFail();
        $profile->update([
            'legal_docs' => [
                ['name' => 'Dokumen Uji'],
                ['number' => 'Tanpa nama harus diabaikan'],
                null,
            ],
        ]);

        $this->get(route('page.detail', 'tentang-kopi'))
            ->assertOk()
            ->assertSee('Dokumen Uji')
            ->assertDontSee('Tanpa nama harus diabaikan');
    }
}
