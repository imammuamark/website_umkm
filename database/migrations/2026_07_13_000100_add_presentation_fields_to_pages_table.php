<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->string('template', 30)->default('standard')->after('slug')->index();
            $table->string('eyebrow', 80)->nullable()->after('template');
            $table->string('subtitle', 220)->nullable()->after('eyebrow');
            $table->string('hero_alt', 180)->nullable()->after('subtitle');
            $table->string('content_image_alt', 180)->nullable()->after('hero_alt');
        });
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropIndex(['template']);
            $table->dropColumn(['template', 'eyebrow', 'subtitle', 'hero_alt', 'content_image_alt']);
        });
    }
};
