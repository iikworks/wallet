<?php

namespace App\Actions\Auth;

use Illuminate\Support\Facades\Auth;

readonly class LogoutUserAction
{
    public function __invoke(): void
    {
        Auth::logout();
    }
}
