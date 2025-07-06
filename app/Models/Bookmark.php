<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BookmarkStatus;
use Illuminate\Contracts\Database\Query\Builder;
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
        'imported_at',
        'recently_imported',
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
        return $this->belongsToMany(Tag::class, 'bookmarks_tags', 'bookmark_id', 'tag_id')->latest();
    }

    /**
     * Scope a query to include bookmarks that have specific tags
     * and match specific site URLs.
     */
    public function scopeWithTagsAndSites(Builder $query, array $tags = [], array $sites = []): Builder
    {
        return $this
            ->when($tags, function (Builder $query) use ($tags) {
                $query->whereHas('tags', function (Builder $query) use ($tags) {
                    $query->whereIn('name', $tags);
                });
            })->when($sites, function (Builder $query) use ($sites) {
                $query->where(function (Builder $query) use ($sites) {
                    foreach ($sites as $site) {
                        $query->orWhere('url', 'LIKE', "%$site%");
                    }
                });
            });
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
            'recently_imported' => 'boolean',
        ];
    }
}
