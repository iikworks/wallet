<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Users\StoreUserAndAuthenticateAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class RegisterController extends Controller
{
    public function store(RegisterRequest $request, StoreUserAndAuthenticateAction $storeUserAction): JsonResponse
    {
        try {
            $result = ($storeUserAction)($request->validated());
            return response()->json([
                'access_token' => $result['access_token'],
                'user' => new UserResource($result['user']),
            ]);
        } catch (UnprocessableEntityHttpException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'errors' => [
                    'phone' => $e->getMessage()
                ],
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
