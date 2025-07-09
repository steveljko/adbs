<?php

declare(strict_types=1);

namespace App\Http\Controllers\Installer;

use Illuminate\View\View;

final class RequirmentsController
{
    public function __invoke(): View
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
            'pdo_pgsql',
        ];

        $extensions = [];
        foreach ($ext as $extension) {
            $extensions[$extension] = [
                'required' => true,
                'current' => extension_loaded(mb_strtolower($extension)) ? 'Enabled' : 'Disabled',
                'satisfied' => extension_loaded(mb_strtolower($extension)),
            ];
        }

        return view('pages.installer.requirements', compact(
            'phpVersion',
            'extensions',
        ));
    }
}
