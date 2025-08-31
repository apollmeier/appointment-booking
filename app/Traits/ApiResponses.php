<?php

namespace App\Traits;

use Symfony\Component\HttpFoundation\JsonResponse;

trait ApiResponses
{
    public function error(array $errors, $status): JsonResponse
    {
        return response()->json(
            [
                'errors' => $errors,
                'status' => $status
            ]
        );
    }
}
