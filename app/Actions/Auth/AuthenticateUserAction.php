<?php

namespace App\Actions\Auth;

use App\Models\User;

readonly class AuthenticateUserAction
{
    public function __invoke(User $user): string
    {
        return $user->createToken('Web Auth')->plainTextToken;
    }
}
