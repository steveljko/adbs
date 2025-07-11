<?php

declare(strict_types=1);

namespace App\Enums;

enum AddonClientStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case PENDING = 'pending';
    case REVOKED = 'revoked';
    case SUSPENDED = 'suspended';
}
