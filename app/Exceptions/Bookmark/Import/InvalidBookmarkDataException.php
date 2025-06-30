<?php

declare(strict_types=1);

namespace App\Exceptions\Bookmark\Import;

/**
 * Thrown when a specific bookmark has invalid or missing required data
 */
final class InvalidBookmarkDataException extends BookmarkImportException
{
    public function __construct(int $bookmarkIndex, string $field, ?string $reason = null)
    {
        $message = "Invalid bookmark data at index {$bookmarkIndex}, field '{$field}'";
        if ($reason) {
            $message .= ": {$reason}";
        }
        parent::__construct($message);
    }
}
