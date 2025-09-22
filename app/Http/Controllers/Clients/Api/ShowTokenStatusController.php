<?php

declare(strict_types=1);

namespace App\Http\Controllers\Clients\Api;

use App\Enums\TokenStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class ShowTokenStatusController
{
    /**
     * Handle currently logged in user token status.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $status = $request->user()->currentAccessToken()->info->status;

        if ($status === TokenStatus::ACTIVE) {
            return new JsonResponse([
                'status' => $status->value,
            ], Response::HTTP_OK);
        }

        return new JsonResponse([
            'status' => $status->value,
        ], Response::HTTP_FORBIDDEN);
    }
}
