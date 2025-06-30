<?php

declare(strict_types=1);

namespace App\Exceptions\Bookmark\Import;

final class InvalidBookmarkStructureException extends BookmarkImportException
{
    public function __construct(string $reason)
    {
        $message = "Invalid bookmark file structure: {$reason}";
        parent::__construct($message);
    }
}
