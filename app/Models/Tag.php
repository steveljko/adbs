<?php

declare(strict_types=1);

namespace App\Models;

use App\Observers\TagObserver;
use App\Policies\TagPolicy;
use App\Traits\Updatable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[ObservedBy(TagObserver::class)]
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

    public function bookmarks(): BelongsToMany
    {
        return $this->belongsToMany(Bookmark::class, 'bookmarks_tags');
    }

    public function user(): BelongsTo
    {
        return $this->BelongsTo(User::class);
    }
}
