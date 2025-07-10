<?php

declare(strict_types=1);

namespace App\Enums;

enum ApiResponseStatus: string
{
    case FAILED = 'failed';
    case SUCCESS = 'success';
}
