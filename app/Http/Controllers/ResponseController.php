<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class ResponseController extends Controller
{
    public function success($data): JsonResponse
    {
        return response()->json(['status' => true, 'data' => $data]);
    }

    public function failed($data): JsonResponse
    {
        return response()->json(['status' => false, 'data' => $data],503);
    }

    public function failedValidate($data): JsonResponse
    {
        return response()->json(['message' => 'The given data was invalid.', 'errors' => $data],422);
    }
}
