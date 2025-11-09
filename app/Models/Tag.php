<?php

declare(strict_types=1);

namespace App\Models;

use App\Policies\TagPolicy;
use App\Traits\Updatable;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Auth;

#[UsePolicy(TagPolicy::class)]
final class Tag extends Model
{
    use Updatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'key',
        'background_color',
        'text_color',
        'description',
        'user_id',
    ];

    /**
     * Get all tag names for the authenticated user
     */
    public static function getForUser(): array
    {
        return self::where('user_id', Auth::id())
            ->pluck('name')
            ->toArray();
    }

    public function bookmarks(): BelongsToMany
    {
        return $this->belongsToMany(Bookmark::class, 'bookmarks_tags');
    }

    public function user(): BelongsTo
    {
        return $this->BelongsTo(User::class);
    }
}
