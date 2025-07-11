<?php

declare(strict_types=1);

use App\Enums\AddonClientStatus;
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
        Schema::create('addon_clients', function (Blueprint $table) {
            $table->id();
            $table->string('token')->unique();
            $table->string('browser');
            $table->string('browser_version')->nullable();
            $table->string('addon_version')->nullable();
            $table->string('user_agent')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->string('status', 20)->default(AddonClientStatus::PENDING);
            $table->timestamp('last_activity_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('token');
            $table->index('status');
            $table->index('last_activity_at');
            $table->index(['browser', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addon_clients');
    }
};
