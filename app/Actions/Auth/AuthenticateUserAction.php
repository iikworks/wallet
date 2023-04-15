<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

readonly class AuthenticateUserAction
{
    public function __invoke(User $user, bool $remember = false): void
    {
        Auth::login($user, $remember);
    }
}
