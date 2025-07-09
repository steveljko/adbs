<?php

declare(strict_types=1);

namespace App\Http\Controllers\Installer;

use App\Http\Requests\User\CreateUserRequest;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

final class UserCreationController
{
    public function __invoke(): View
    {
        return view('pages.installer.user');
    }

    public function run(CreateUserRequest $request): Response
    {
        User::create($request->only('name', 'email', 'password'));

        $this->createInstalledFile();

        return htmx()->redirect(route('auth.login'))->response(null);
    }

    public function skip(): Response
    {
        $this->createInstalledFile();

        return htmx()->redirect(route('auth.login'))->response(null);
    }

    private function createInstalledFile(): void
    {
        $dateTimestamp = date('Y/m/d h:i:sa');
        File::put(storage_path('installed'), $dateTimestamp);
    }
}
