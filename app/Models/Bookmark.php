<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BookmarkStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

final class Bookmark extends Model
{
    /** @use HasFactory<\Database\Factories\BookmarkFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'url',
        'title',
        'favicon',
        'status',
        'user_id',
    ];

    /**
     * User that created this bookmark
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * All tags associated to bookmarks
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'bookmarks_tags', 'bookmark_id', 'tag_id');
    }

    /**
     * Encode url and decode on get value
     */
    protected function url(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => urlencode($value),
            get: fn (string $value) => urldecode($value)
        );
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => BookmarkStatus::class,
        ];
    }
}
