<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function getPreferences(Request $request): JsonResponse
    {
        return response()->json(auth()->user()->preferences);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function setPreferences(Request $request): JsonResponse
    {
        $user = auth()->user();

        $user->update(['preferences' => json_decode(json_encode($request->preferences))]);
        return response()->json(
            ['message' => 'success']
        );
    }
}
