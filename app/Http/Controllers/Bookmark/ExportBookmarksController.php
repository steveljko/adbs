<?php

declare(strict_types=1);

namespace App\Http\Controllers\Bookmark;

use App\Http\Actions\Bookmark\ExportBookmarksAction;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

final class ExportBookmarksController
{
    public function __invoke(ExportBookmarksAction $action): Response
    {
        $data = $action->execute(for: Auth::user());
        $encodedData = json_encode($data);

        return response($encodedData, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="bookmarks.json"',
        ]);
    }
}
