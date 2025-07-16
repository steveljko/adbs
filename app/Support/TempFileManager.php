<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * This class is responsible for managing temporary files used in bookmark imports.
 */
final class TempFileManager
{
    private const TEMP_DIRECTORY = 'temp';

    private const FILE_PREFIX = 'bookmarks_';

    private const FILE_EXTENSION = '.json';

    public function store(UploadedFile $file): string
    {
        $fileName = $this->generateFileName();
        Storage::putFileAs(self::TEMP_DIRECTORY, $file, $fileName);

        return $fileName;
    }

    public function exists(string $fileName): bool
    {
        return Storage::exists($this->getStoragePath($fileName));
    }

    public function getPath(string $fileName): string
    {
        return Storage::path($this->getStoragePath($fileName));
    }

    public function delete(string $fileName): bool
    {
        return Storage::delete($this->getStoragePath($fileName));
    }

    private function generateFileName(): string
    {
        return self::FILE_PREFIX.Str::uuid().self::FILE_EXTENSION;
    }

    private function getStoragePath(string $fileName): string
    {
        return self::TEMP_DIRECTORY.'/'.$fileName;
    }
}
