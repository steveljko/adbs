<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\Facades\File;

final class EnvironmentManager
{
    public function updateKey(string $key, string $value): void
    {
        $envContent = $this->getEnvContent();
        $pattern = "/^{$key}=.*/m";
        $replacement = "{$key}={$value}";

        if (preg_match($pattern, $envContent)) {
            $envContent = preg_replace($pattern, $replacement, $envContent);
        } else {
            $envContent .= "\n{$replacement}";
        }

        $this->saveEnvContent($envContent);
    }

    public function updateMultipleKeys(array $data): void
    {
        $envContent = $this->getEnvContent();

        foreach ($data as $key => $value) {
            $pattern = "/^{$key}=.*/m";
            $replacement = "{$key}={$value}";

            if (preg_match($pattern, $envContent)) {
                $envContent = preg_replace($pattern, $replacement, $envContent);
            } else {
                $envContent .= "\n{$replacement}";
            }
        }

        $this->saveEnvContent($envContent);
    }

    public function removeKey(string $key): void
    {
        $envContent = $this->getEnvContent();
        $pattern = "/^{$key}=.*\n?/m";
        $envContent = preg_replace($pattern, '', $envContent);
        $envContent = preg_replace("/\n{2,}/", "\n", $envContent);

        $this->saveEnvContent($envContent);
    }

    public function removeMultipleKeys(array $keys): void
    {
        $envContent = $this->getEnvContent();

        foreach ($keys as $key) {
            $pattern = "/^{$key}=.*\n?/m";
            $envContent = preg_replace($pattern, '', $envContent);
        }

        $envContent = preg_replace("/\n{2,}/", "\n", $envContent);
        $this->saveEnvContent($envContent);
    }

    public function appendKey(string $key, string $value): void
    {
        $envContent = $this->getEnvContent();
        $envContent .= "\n{$key}={$value}";

        $this->saveEnvContent($envContent);
    }

    public function appendKeyAfter(string $key, string $value, string $afterKey): void
    {
        $envContent = $this->getEnvContent();
        $pattern = "/^{$afterKey}=.*/m";

        if (preg_match($pattern, $envContent)) {
            $replacement = "$0\n{$key}={$value}";
            $envContent = preg_replace($pattern, $replacement, $envContent);
        } else {
            // if afterKey doesn't exist, just append at the end
            $envContent .= "\n{$key}={$value}";
        }

        $this->saveEnvContent($envContent);
    }

    public function updateDatabaseEnvironment(string $driver, array $config): void
    {
        // remove all database keys first
        $this->removeMultipleKeys([
            'DB_HOST',
            'DB_PORT',
            'DB_DATABASE',
            'DB_USERNAME',
            'DB_PASSWORD',
        ]);

        switch ($driver) {
            case 'sqlite':
                $this->updateKey('DB_CONNECTION', 'sqlite');
                $this->appendKeyAfter('DB_DATABASE', $config['database'], 'DB_CONNECTION');
                break;
            case 'mysql':
                $this->updateKey('DB_CONNECTION', 'mysql');
                $this->appendKeyAfter('DB_HOST', $config['host'], 'DB_CONNECTION');
                $this->appendKeyAfter('DB_PORT', (string) $config['port'], 'DB_HOST');
                $this->appendKeyAfter('DB_DATABASE', $config['database'], 'DB_PORT');
                $this->appendKeyAfter('DB_USERNAME', $config['username'], 'DB_DATABASE');
                $this->appendKeyAfter('DB_PASSWORD', $config['password'], 'DB_USERNAME');
                break;
            case 'pgsql':
                $this->updateKey('DB_CONNECTION', 'pgsql');
                $this->appendKeyAfter('DB_HOST', $config['host'], 'DB_CONNECTION');
                $this->appendKeyAfter('DB_PORT', (string) $config['port'], 'DB_HOST');
                $this->appendKeyAfter('DB_DATABASE', $config['database'], 'DB_PORT');
                $this->appendKeyAfter('DB_USERNAME', $config['username'], 'DB_DATABASE');
                $this->appendKeyAfter('DB_PASSWORD', $config['password'], 'DB_USERNAME');
                break;
        }
    }

    private function getEnvContent(): string
    {
        return File::get(base_path('.env'));
    }

    private function saveEnvContent(string $content): void
    {
        File::put(base_path('.env'), $content);
    }
}
