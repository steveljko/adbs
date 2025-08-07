<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookmarks', function (Blueprint $table) {
            $table->datetime('imported_at')->nullable()->index();
            $table->boolean('can_undo')->default(false)->after('imported_at');

            $table->index(['user_id', 'can_undo', 'imported_at'], 'idx_bookmarks_undo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookmarks', function (Blueprint $table) {
            $table->dropIndex('idx_bookmarks_undo');
            $table->dropColumn(['can_undo', 'imported_at']);
        });
    }
};
