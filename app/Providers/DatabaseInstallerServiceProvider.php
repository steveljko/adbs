<?php

declare(strict_types=1);

namespace App\Providers;

use App\Support\Database\DatabaseConnectionTester;
use App\Support\DatabaseManager;
use App\Support\EnvironmentManager;
use Illuminate\Support\ServiceProvider;

final class DatabaseInstallerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(DatabaseConnectionTester::class);
        $this->app->bind(EnvironmentManager::class);

        $this->app->bind(DatabaseManager::class, function ($app) {
            return new DatabaseManager(
                $app->make(DatabaseConnectionTester::class),
                $app->make(EnvironmentManager::class),
            );
        });
    }
}
