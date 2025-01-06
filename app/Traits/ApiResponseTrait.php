<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponseTrait
{
    public function sendResponse($data, $message = "Success", $status = 200): JsonResponse
    {
        return response()->json([
            "status" => 'success',
            'message' => $message,
            'data' => $data
        ], $status);
    }
    public function sendError($message, $status = 400): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message
        ], $status);
    }

    protected function errorResponse($message)
    {
        return response()->json([
            'message' => $message,
            'status' => '0',
        ]);
    }
}
