<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->string('about_values_title', 100)->nullable()->after('content_image_alt');
            $table->string('about_primary_label', 80)->nullable()->after('about_values_title');
            $table->string('about_secondary_label', 80)->nullable()->after('about_primary_label');
        });
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn(['about_values_title', 'about_primary_label', 'about_secondary_label']);
        });
    }
};
