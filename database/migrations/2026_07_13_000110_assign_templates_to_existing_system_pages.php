<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('pages')
            ->whereIn('slug', ['tentang-kopi', 'tentang-panama'])
            ->update([
                'template' => 'about',
                'eyebrow' => 'Cerita & Nilai Kami',
            ]);

        DB::table('pages')
            ->where('slug', 'lokasi')
            ->update([
                'template' => 'locations',
                'eyebrow' => 'Temui Kami',
            ]);
    }

    public function down(): void
    {
        DB::table('pages')
            ->whereIn('slug', ['tentang-kopi', 'tentang-panama', 'lokasi'])
            ->update([
                'template' => 'standard',
                'eyebrow' => null,
            ]);
    }
};
