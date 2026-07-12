<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('digital_menu_settings')->where('layout', 'grid')->update(['layout' => 'balanced']);
        DB::table('digital_menu_settings')->where('layout', 'list')->update(['layout' => 'compact']);
    }

    public function down(): void
    {
        DB::table('digital_menu_settings')->where('layout', 'balanced')->update(['layout' => 'grid']);
        DB::table('digital_menu_settings')->where('layout', 'compact')->update(['layout' => 'list']);
    }
};
