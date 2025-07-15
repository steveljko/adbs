<?php

declare(strict_types=1);

namespace App\Http\Controllers\Installer;

use Illuminate\Http\Response;
use Illuminate\View\View;

final class RequirmentsController
{
    public function __invoke(): View|Response
    {
        $phpVersion = PHP_VERSION;

        $ext = [
            'BCMath',
            'Ctype',
            'Fileinfo',
            'JSON',
            'Mbstring',
            'OpenSSL',
            'PDO',
            'Tokenizer',
            'XML',
            'cURL',
            'GD',
            'Zip',
        ];

        $extensions = [];
        foreach ($ext as $extension) {
            $extensions[$extension] = [
                'required' => true,
                'satisfied' => extension_loaded(mb_strtolower($extension)),
            ];
        }

        if (htmx()->isRequest()) {
            return htmx()
                ->redirect(route('installer.database'))
                ->response();
        }

        return view('pages.installer.requirements', compact(
            'phpVersion',
            'extensions',
        ));
    }
}
