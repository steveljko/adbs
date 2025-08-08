<?php

declare(strict_types=1);

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;

final class ChangeViewTypeController
{
    public function __invoke(Request $request)
    {
        $viewType = $request->get('view_type');

        preferences()->set('view_type', $viewType);

        return htmx()
            ->toast(type: 'success', text: 'View type succesfully changed!')
            ->response(view('partials.settings.view-type')->fragment('choose'));
    }
}
