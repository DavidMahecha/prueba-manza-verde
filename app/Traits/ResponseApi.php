<?php

namespace App\Traits;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;

trait ResponseApi {

    public function successResponse(mixed $data, ?int $statusCode = Response::HTTP_OK): JsonResponse {
        return response()->json($data, $statusCode);
    }

    public function errorResponse(mixed $errors, int $statusCode): JsonResponse {
        return response()->json(['errors' => $errors], $statusCode);
    }
}