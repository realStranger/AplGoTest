<?php

namespace App\Http\Api\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class ApiController extends Controller
{
    /**
     * Success response
     *
     * @param array $result
     * @param bool $prettyPrint
     * @return JsonResponse
     */
    protected function success($result = [], bool $prettyPrint = false): JsonResponse
    {
        $response = ['success' => true];
        if (!empty($result)) {
            $response['result'] = $result;
        }
        return response()->json($response, 200, [], $prettyPrint ? JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE : 0);
    }
}
