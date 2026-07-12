<?php

namespace Tests\Feature;

use App\Filament\Pages\FooterSettings;
use App\Models\MenuItem;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FooterSettingsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
    }

    public function test_footer_renders_dynamic_safe_content(): void
    {
        SiteSetting::set('footer_description', 'Deskripsi footer dinamis untuk pengujian.');
        SiteSetting::set('footer_cta_enabled', true);
        SiteSetting::set('footer_cta_title', 'Mari berdiskusi');
        SiteSetting::set('footer_cta_button_url', '/kontak');
        SiteSetting::set('footer_copyright', '© {year} {business_name}.');
        SiteSetting::set('instagram_url', 'javascript:alert(1)');

        MenuItem::create([
            'label' => 'Menu Nonaktif Rahasia',
            'type' => 'custom',
            'url' => 'https://example.com',
            'sort_order' => 99,
            'is_active' => false,
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Deskripsi footer dinamis untuk pengujian.')
            ->assertSee('Mari berdiskusi')
            ->assertSee('© '.now()->year.' Panama Corner.', false)
            ->assertDontSee('javascript:alert(1)', false)
            ->assertDontSee('Menu Nonaktif Rahasia');
    }

    public function test_footer_sections_can_be_hidden(): void
    {
        SiteSetting::set('footer_show_socials', false);
        SiteSetting::set('footer_show_navigation', false);
        SiteSetting::set('footer_show_legal', false);
        SiteSetting::set('footer_show_contact', false);
        SiteSetting::set('instagram_url', 'https://instagram.example/footer-marker');
        SiteSetting::set('footer_legal_title', 'Footer Legal Marker');
        SiteSetting::set('footer_contact_title', 'Footer Contact Marker');

        $response = $this->get(route('home'))->assertOk();

        $this->assertStringNotContainsString('instagram.example/footer-marker', $response->getContent());
        $this->assertStringNotContainsString('Footer Legal Marker', $response->getContent());
        $this->assertStringNotContainsString('Footer Contact Marker', $response->getContent());
    }

    public function test_only_authorized_users_can_access_footer_settings(): void
    {
        $admin = User::where('email', 'admin@panamacorner.com')->firstOrFail();

        $this->actingAs($admin)
            ->get('/admin/footer-settings')
            ->assertRedirect('/admin/theme-customizer?design-tab=footer');

        $preview = $this->actingAs($admin)
            ->get(route('admin.footer-preview'))
            ->assertOk()
            ->assertSee('Kafe di Condongcatur dengan pilihan makanan, camilan, kopi, dan minuman nonkopi.')
            ->assertSee('bg-[#09121f]', false);

        $this->assertStringContainsString('no-store', (string) $preview->headers->get('Cache-Control'));
        $this->assertStringContainsString('private', (string) $preview->headers->get('Cache-Control'));

        $unauthorized = User::factory()->create(['is_active' => true]);
        $this->actingAs($unauthorized)->get('/admin/footer-settings')->assertRedirect('/admin/login');
        $this->actingAs($unauthorized)->get(route('admin.footer-preview'))->assertForbidden();
    }

    public function test_footer_settings_are_consolidated_into_theme_customizer_navigation(): void
    {
        $this->assertFalse(FooterSettings::shouldRegisterNavigation());

        $admin = User::where('email', 'admin@panamacorner.com')->firstOrFail();

        $this->actingAs($admin)
            ->get('/admin/theme-customizer?design-tab=footer')
            ->assertOk()
            ->assertSee('Design Workspace')
            ->assertSee('Konfigurasi Footer')
            ->assertSee('Preview Website Aktual');
    }
}
