<?php

declare(strict_types=1);

namespace App\Http\Actions\Bookmark;

use App\Models\Bookmark;
use App\Models\User;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Collection;

final class ExportBookmarksAction
{
    public function execute(User $for, ?string $password = null): array
    {
        $bookmarks = $this->getBookmarks(user: $for);

        if ($password) {
            return $this->encryptBookmarks($bookmarks, $password);
        }

        return [
            'encrypted' => false,
            'data' => $bookmarks,
            'exported_at' => now()->toISOString(),
        ];
    }

    private function getBookmarks(User $user): array
    {
        $bookmarks = Bookmark::query()
            ->where('user_id', $user->id)
            ->get();

        return $this->formatBookmarksForExport(bookmarks: $bookmarks);
    }

    private function formatBookmarksForExport(
        Collection $bookmarks,
        bool $includeTags = true,
        bool $includeTimestamps = true,
    ): array {
        $exportedBookmarks = $bookmarks->map(function (Bookmark $bookmark) use ($includeTags, $includeTimestamps) {
            $data = [
                'url' => $bookmark->url,
                'title' => $bookmark->title,
                'status' => $bookmark->status->value,
            ];

            // add timestamps
            if ($includeTimestamps) {
                $data['created_at'] = $bookmark->created_at?->toISOString();
                $data['updated_at'] = $bookmark->updated_at?->toISOString();
            }

            // add relations
            if ($includeTags) {
                $data['tags'] = $bookmark->tags->pluck('name')->toArray();
            }

            return $data;
        });

        return $exportedBookmarks->values()->toArray();
    }

    private function encryptBookmarks(array $bookmarks, string $password): array
    {
        $key = hash('sha256', $password, true);

        $encrypter = new Encrypter($key, 'AES-256-CBC');

        $encryptedData = $encrypter->encrypt(json_encode($bookmarks));

        return [
            'encrypted' => true,
            'data' => $encryptedData,
            'exported_at' => now()->toISOString(),
        ];
    }
}
