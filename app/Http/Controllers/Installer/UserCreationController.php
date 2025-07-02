<?php

declare(strict_types=1);

namespace App\Http\Controllers\Installer;

use App\Http\Requests\User\CreateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\File;

final class UserCreationController
{
    public function __invoke()
    {
        return view('resources.installer.user');
    }

    public function run(CreateUserRequest $request)
    {
        User::create($request->only('name', 'email', 'password'));

        $dateTimestamp = date('Y/m/d h:i:sa');
        File::put(storage_path('installed'), $dateTimestamp);

        return htmx()->redirect(route('auth.login'))->response(null);
    }
}
