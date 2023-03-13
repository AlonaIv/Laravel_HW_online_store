<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BaseController extends Controller
{
    protected function notAllowedResponse(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'massage' => 'You are not allowed to this'
        ], Response::HTTP_FORBIDDEN);
    }

    protected function userCan(string $ability): bool
    {
        return auth()->user()->tokenCan($ability) || auth()->user()->tokenCan('full');
    }
}
