<?php

namespace App\Traits;

use Symfony\Component\HttpFoundation\JsonResponse;

trait ApiResponses
{
    public function error(array $errors, $status): JsonResponse
    {
        return response()->json([
            'message' => $errors[0] . (count($errors) > 1 ? ' (and' . count($errors) - 1 . ' more errors)' : ''),
            'errors' => $errors
        ], $status);
    }

    public function success($data, $status = 200): JsonResponse
    {
        return response()->json([
            'data' => $data,
        ], $status);
    }
}
