<?php

declare(strict_types=1);

namespace App\Http\Controllers\Clients;

use App\Models\PersonalAccessToken;

final class ShowClientController
{
    public function __invoke(PersonalAccessToken $personalAccessToken)
    {
        $personalAccessToken->load('info');
        return view('partials.clients.show', compact('personalAccessToken'));
    }
}
