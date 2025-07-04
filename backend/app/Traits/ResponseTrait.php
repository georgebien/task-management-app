<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpFoundation\Response;

trait ResponseTrait
{
    /**
     * Generate a success response.
     *
     * @param string $message
     * @param mixed $data
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    protected function success(string $message, $data = []): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], Response::HTTP_OK);
    }

    /**
     * Generate an error response.
     *
     * @param string $message
     * @param int $statusCode
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    protected function error(string $message, int $statusCode = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $statusCode);
    }
}