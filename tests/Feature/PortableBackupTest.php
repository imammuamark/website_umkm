<?php

namespace Tests\Feature;

use App\Models\SiteSetting;
use App\Models\User;
use App\Support\PortableBackupService;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Tests\TestCase;
use ZipArchive;

class PortableBackupTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_page_and_downloads_require_settings_permission(): void
    {
        $admin = User::factory()->create(['is_active' => true]);
        $this->seed(RolesAndPermissionsSeeder::class);
        $admin->givePermissionTo('manage settings');

        $this->get('/admin/backup-migration')->assertRedirect('/admin/login');
        $this->get(route('admin.portability.manifest'))->assertRedirect('/login');

        $this->actingAs($admin)
            ->get('/admin/backup-migration')
            ->assertOk()
            ->assertSee('Paket Portabel Website')
            ->assertSee('Impor Data')
            ->assertSee('Impor Media');

        $this->actingAs($admin)
            ->get(route('admin.portability.manifest'))
            ->assertOk()
            ->assertHeader('Content-Type', 'application/json; charset=UTF-8')
            ->assertHeader('X-Content-Type-Options', 'nosniff');

        if (class_exists(ZipArchive::class)) {
            Storage::fake('public');
            Storage::disk('public')->put('products/test.png', 'test-image');

            $this->actingAs($admin)
                ->get(route('admin.portability.media', 1))
                ->assertOk()
                ->assertHeader('Content-Type', 'application/octet-stream')
                ->assertHeader('X-Download-Options', 'noopen')
                ->assertDownload('website-umkm-media-part-01.umkm-media');
        }
    }

    public function test_manifest_is_portable_and_excludes_sensitive_settings(): void
    {
        SiteSetting::set('site_title', 'UMKM Test');
        SiteSetting::set('integration_secret_token', 'must-not-leave-server');

        $service = app(PortableBackupService::class);
        $json = $service->exportJson();
        $inspection = $service->inspect($json);

        $this->assertTrue($inspection['valid']);
        $this->assertSame('sqlite', $inspection['source_driver']);
        $this->assertStringContainsString('UMKM Test', $json);
        $this->assertStringNotContainsString('must-not-leave-server', $json);
        $this->assertStringNotContainsString('"users":', $json);
    }

    public function test_changed_manifest_is_rejected_before_database_write(): void
    {
        SiteSetting::set('site_title', 'Original');
        $service = app(PortableBackupService::class);
        $tampered = str_replace('Original', 'Changed!', $service->exportJson());

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Checksum tidak cocok');

        $service->import($tampered, 'merge', 1);
        $this->assertDatabaseHas('site_settings', ['key' => 'site_title', 'value' => 'Original']);
    }

    public function test_replace_import_restores_portable_content_atomically(): void
    {
        SiteSetting::set('site_title', 'Backup Value');
        $service = app(PortableBackupService::class);
        $json = $service->exportJson();

        DB::table('site_settings')->where('key', 'site_title')->update(['value' => 'Current Value']);
        $result = $service->import($json, 'replace', 1);

        $this->assertSame('replace', $result['mode']);
        $this->assertDatabaseHas('site_settings', ['key' => 'site_title', 'value' => 'Backup Value']);
    }

    public function test_media_import_accepts_only_tagged_safe_archives(): void
    {
        if (! class_exists(ZipArchive::class)) {
            $this->markTestSkipped('PHP ZIP is not available.');
        }

        Storage::fake('public');
        $path = tempnam(sys_get_temp_dir(), 'portable-media-');
        $zip = new ZipArchive;
        $zip->open($path, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        $zip->addFromString('media-part.json', json_encode([
            'format' => PortableBackupService::FORMAT,
            'version' => PortableBackupService::VERSION,
            'part' => 1,
        ], JSON_THROW_ON_ERROR));
        $zip->addFromString('products/menu-test.png', 'png-bytes');
        $zip->close();

        $result = app(PortableBackupService::class)->importMediaArchive($path);

        $this->assertSame(1, $result['files']);
        Storage::disk('public')->assertExists('products/menu-test.png');
        @unlink($path);
    }

    public function test_media_import_rejects_path_traversal(): void
    {
        if (! class_exists(ZipArchive::class)) {
            $this->markTestSkipped('PHP ZIP is not available.');
        }

        $path = tempnam(sys_get_temp_dir(), 'portable-media-');
        $zip = new ZipArchive;
        $zip->open($path, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        $zip->addFromString('media-part.json', json_encode([
            'format' => PortableBackupService::FORMAT,
            'version' => PortableBackupService::VERSION,
            'part' => 1,
        ], JSON_THROW_ON_ERROR));
        $zip->addFromString('../payload.php', '<?php echo 1;');
        $zip->close();

        try {
            app(PortableBackupService::class)->importMediaArchive($path);
            $this->fail('Unsafe ZIP was accepted.');
        } catch (RuntimeException $exception) {
            $this->assertStringContainsString('tidak aman', $exception->getMessage());
        } finally {
            @unlink($path);
        }
    }
}
