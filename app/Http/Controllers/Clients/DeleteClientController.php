<?php

declare(strict_types=1);

namespace App\Http\Controllers\Clients;

use App\Models\PersonalAccessToken;

final class DeleteClientController
{
    public function __invoke(PersonalAccessToken $personalAccessToken)
    {
        return view('partials.clients.delete', compact('personalAccessToken'));
    }
}
