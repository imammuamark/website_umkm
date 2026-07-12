<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->string('editor_mode', 20)->default('visual')->after('content');
            $table->string('workflow_status', 20)->default('draft')->after('status')->index();
            $table->foreignId('reviewed_by')->nullable()->after('author_id')->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable()->after('reviewed_by');
            $table->foreignId('published_by')->nullable()->after('reviewed_at')->constrained('users')->nullOnDelete();
            $table->unsignedInteger('revision')->default(1)->after('reading_time');
            $table->unsignedInteger('lock_version')->default(1)->after('revision');
            $table->softDeletes();
        });

        DB::table('articles')
            ->where('status', 'published')
            ->where('published_at', '>', now())
            ->update(['workflow_status' => 'scheduled']);

        DB::table('articles')
            ->where('status', 'published')
            ->where(fn ($query) => $query->whereNull('published_at')->orWhere('published_at', '<=', now()))
            ->update(['workflow_status' => 'published']);
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropForeign(['reviewed_by']);
            $table->dropForeign(['published_by']);
            $table->dropIndex(['workflow_status']);
            $table->dropColumn([
                'editor_mode',
                'workflow_status',
                'reviewed_by',
                'reviewed_at',
                'published_by',
                'revision',
                'lock_version',
                'deleted_at',
            ]);
        });
    }
};
