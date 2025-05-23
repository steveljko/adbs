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
        Schema::create('bookmarks_tags', function (Blueprint $table) {
            $table->foreignId('bookmark_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('tag_id')
                ->constrained()
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookmarks_tags');
    }
};
