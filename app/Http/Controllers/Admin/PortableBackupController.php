<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Support\PortableBackupService;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PortableBackupController extends Controller
{
    public function manifest(PortableBackupService $backup): Response
    {
        $filename = 'website-umkm-data-'.now()->format('Y-m-d-His').'.json';
        ActivityLog::log('export_portable_data', 'Mengunduh manifest data CMS untuk migrasi.');

        return response($backup->exportJson(), 200, [
            'Content-Type' => 'application/json; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            'Cache-Control' => 'no-store, private',
            'X-Content-Type-Options' => 'nosniff',
            'X-Robots-Tag' => 'noindex, nofollow',
        ]);
    }

    public function media(int $part, PortableBackupService $backup): BinaryFileResponse
    {
        abort_unless($part >= 1 && $part <= count($backup->mediaParts()), 404);
        $path = $backup->createMediaPart($part);
        ActivityLog::log('export_portable_media', "Mengunduh paket media bagian {$part}.");

        return response()->download(
            $path,
            'website-umkm-media-part-'.str_pad((string) $part, 2, '0', STR_PAD_LEFT).'.umkm-media',
            [
                'Content-Type' => 'application/octet-stream',
                'Cache-Control' => 'no-store, private',
                'X-Content-Type-Options' => 'nosniff',
                'X-Download-Options' => 'noopen',
                'X-Robots-Tag' => 'noindex, nofollow',
            ],
        )->deleteFileAfterSend(true);
    }
}
