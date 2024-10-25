<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class ApiController extends Controller
{
    public function success(string $message, array|null $data, int $code = 200): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'code' => $code,
            'data' => $data,
        ], $code);
    }

    public function error(string $message, int $code = 400):JsonResponse
    {
        return $this->success($message, null, $code);
    }
}
