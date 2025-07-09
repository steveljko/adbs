<?php

declare(strict_types=1);

namespace App\Http\Controllers\Installer;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

final class WelcomeController
{
    public function __invoke()
    {
        return view('pages.installer.welcome');
    }

    public function run(Request $request)
    {
        Artisan::call('config:clear');

        $this->updateEnv(['APP_URL' => $request->url]);

        Artisan::call('config:cache');

        return htmx()
            ->redirect(route('installer.requirements'))
            ->response();
    }

    private function updateEnv(array $data): void
    {
        $envFile = base_path('.env');
        $envContent = File::get($envFile);

        foreach ($data as $key => $value) {
            $pattern = "/^{$key}=.*/m";
            $replacement = "{$key}={$value}";

            if (preg_match($pattern, $envContent)) {
                $envContent = preg_replace($pattern, $replacement, $envContent);
            } else {
                $envContent .= "\n{$replacement}";
            }
        }

        File::put($envFile, $envContent);
    }
}
