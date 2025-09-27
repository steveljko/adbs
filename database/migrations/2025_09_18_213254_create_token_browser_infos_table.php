<?php

declare(strict_types=1);

use App\Enums\TokenStatus;
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
            $table->foreignId('access_token_id')->nullable()->constrained('personal_access_tokens')->onDelete('set null');
            $table->foreignId('refresh_token_id')->nullable()->constrained('personal_access_tokens')->onDelete('set null');
            $table->string('browser_identifier');
            $table->string('browser')->nullable();
            $table->string('browser_version')->nullable();
            $table->string('addon_version')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->default(TokenStatus::PENDING);
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
