<?php

declare(strict_types=1);

namespace App\Exceptions\Bookmark\Import;

/**
 * Thrown when the bookmark file is empty or contains no bookmarks
 */
final class EmptyBookmarkFileException extends BookmarkImportException
{
    public function __construct()
    {
        parent::__construct('Bookmark file contains no bookmarks to import.');
    }
}
