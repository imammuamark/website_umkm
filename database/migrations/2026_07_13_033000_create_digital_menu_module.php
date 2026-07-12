<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('digital_menu_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_enabled')->default(true);
            $table->string('title', 100)->default('Menu Panama Corner');
            $table->string('subtitle', 220)->nullable();
            $table->string('layout', 20)->default('grid');
            $table->boolean('show_search')->default(true);
            $table->boolean('show_images')->default(true);
            $table->boolean('show_descriptions')->default(true);
            $table->boolean('show_stock')->default(true);
            $table->boolean('show_badges')->default(true);
            $table->boolean('show_unavailable')->default(true);
            $table->boolean('use_theme_colors')->default(true);
            $table->string('primary_color', 7)->default('#0F766E');
            $table->string('accent_color', 7)->default('#F59E0B');
            $table->boolean('cta_enabled')->default(false);
            $table->string('cta_label', 60)->nullable();
            $table->string('cta_url', 500)->nullable();
            $table->boolean('allow_indexing')->default(false);
            $table->timestamps();
        });

        Schema::create('digital_menu_access_points', function (Blueprint $table) {
            $table->id();
            $table->string('public_id', 24)->unique();
            $table->string('label', 100);
            $table->string('type', 20)->default('table');
            $table->foreignId('category_id')->nullable()->constrained('product_categories')->nullOnDelete();
            $table->boolean('is_active')->default(true)->index();
            $table->unsignedBigInteger('scans_count')->default(0);
            $table->timestamp('last_scanned_at')->nullable();
            $table->timestamps();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_menu_visible')->default(true)->index();
            $table->unsignedInteger('menu_sort_order')->default(0)->index();
            $table->string('menu_short_description', 220)->nullable();
            $table->string('menu_badge', 40)->nullable();
        });

        Schema::table('product_categories', function (Blueprint $table) {
            $table->boolean('is_menu_visible')->default(true)->index();
            $table->unsignedInteger('menu_sort_order')->default(0)->index();
            $table->string('menu_display_name', 80)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('product_categories', function (Blueprint $table) {
            $table->dropColumn(['is_menu_visible', 'menu_sort_order', 'menu_display_name']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['is_menu_visible', 'menu_sort_order', 'menu_short_description', 'menu_badge']);
        });

        Schema::dropIfExists('digital_menu_access_points');
        Schema::dropIfExists('digital_menu_settings');
    }
};
