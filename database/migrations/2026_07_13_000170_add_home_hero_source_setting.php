<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('site_settings')->updateOrInsert(
            ['key' => 'hero_image_source'],
            ['value' => 'url']
        );
    }

    public function down(): void
    {
        DB::table('site_settings')->where('key', 'hero_image_source')->delete();
    }
};
