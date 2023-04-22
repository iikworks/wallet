<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\AuthenticateUserByCredentialsAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    public function login(LoginRequest $request, AuthenticateUserByCredentialsAction $authenticateUserAction): JsonResponse
    {
        try {
            $result = ($authenticateUserAction)($request->validated());
            return response()->json([
                'access_token' => $result['access_token'],
                'user' => new UserResource($result['user']),
            ]);
        } catch (UnauthorizedException) {
            return response()->json([
                'message' => __('auth.failed'),
                'errors' => [
                    'phone' => __('auth.failed')
                ],
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
