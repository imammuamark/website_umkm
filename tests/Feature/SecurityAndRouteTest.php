<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\Product;
use App\Models\ProductCategory;
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
}
