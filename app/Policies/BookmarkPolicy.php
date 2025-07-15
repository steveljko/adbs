<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Bookmark;
use App\Models\User;

final class BookmarkPolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Bookmark $bookmark): bool
    {
        return $bookmark->user()->is($user);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Bookmark $bookmark): bool
    {
        return $bookmark->user()->is($user);
    }
}
