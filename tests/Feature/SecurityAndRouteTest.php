<?php

namespace Tests\Feature;

use App\Models\BusinessProfile;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
        $routes = ['/', '/profil', '/produk', '/artikel', '/lokasi', '/kontak'];

        foreach ($routes as $route) {
            $response = $this->get($route);
            $response->assertStatus(200);

            // Check that security headers middleware works
            $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
            $response->assertHeader('X-Content-Type-Options', 'nosniff');
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
    }

    public function test_location_page_rejects_untrusted_map_embed_html(): void
    {
        SiteSetting::set(
            'google_maps_embed',
            '<iframe src="https://evil.example/collect"></iframe><script>alert(1)</script>'
        );

        $response = $this->get(route('lokasi'));

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

        $this->get(route('lokasi'))
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

        $this->get(route('profil'))
            ->assertOk()
            ->assertSee('Dokumen Uji')
            ->assertDontSee('Tanpa nama harus diabaikan');
    }
}
