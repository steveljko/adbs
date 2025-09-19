<?php

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
        Schema::create('token_browser_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personal_access_token_id')->constrained()->onDelete('cascade');
            $table->string('browser')->nullable();
            $table->string('browser_version')->nullable();
            $table->string('addon_version')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('token_browser_infos');
    }
};
