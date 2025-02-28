<?php

declare(strict_types=1);

namespace App\Enums;

enum BookmarkStatus: string
{
    case DRAFT = 'draft';
    case PUBLISHED = 'published';
}
