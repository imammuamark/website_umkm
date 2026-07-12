<?php

namespace Tests\Feature;

use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WhatsAppSettingsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
    }

    /**
     * Test that WhatsApp FAB is visible by default.
     */
    public function test_whatsapp_fab_is_visible_by_default(): void
    {
        SiteSetting::set('show_whatsapp_fab', true);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Hubungi Kami di WhatsApp');
    }

    /**
     * Test that WhatsApp FAB is hidden when show_whatsapp_fab is set to false.
     */
    public function test_whatsapp_fab_is_hidden_when_disabled(): void
    {
        SiteSetting::set('show_whatsapp_fab', false);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertDontSee('Hubungi Kami di WhatsApp');
    }

    /**
     * Test that WhatsApp Order Button is visible by default.
     */
    public function test_whatsapp_order_button_is_visible_by_default(): void
    {
        SiteSetting::set('enable_whatsapp_order', true);

        $response = $this->get('/produk/es-kopi-gula-aren');
        $response->assertStatus(200);
        $response->assertSee('Pesan via WhatsApp (Instan)');
    }

    /**
     * Test that WhatsApp Order Button is hidden when enable_whatsapp_order is set to false.
     */
    public function test_whatsapp_order_button_is_hidden_when_disabled(): void
    {
        SiteSetting::set('enable_whatsapp_order', false);

        $response = $this->get('/produk/es-kopi-gula-aren');
        $response->assertStatus(200);
        $response->assertDontSee('Pesan via WhatsApp (Instan)');
    }

    /**
     * Test that Catalog Page conditional grid applies correctly based on enable_whatsapp_order.
     */
    public function test_catalog_quick_view_renders_wa_button_conditional_on_setting(): void
    {
        // When enabled
        SiteSetting::set('enable_whatsapp_order', true);
        $response = $this->get('/produk');
        $response->assertStatus(200);
        $response->assertSee('sm:grid-cols-2');
        $response->assertSee('Pesan via WhatsApp');

        // When disabled
        SiteSetting::set('enable_whatsapp_order', false);
        $response2 = $this->get('/produk');
        $response2->assertStatus(200);
        $response2->assertSee('grid-cols-1');
        $response2->assertDontSee('Pesan via WhatsApp');
    }
}
