<?php

namespace App\Actions\Auth;

use App\Actions\Users\FormatNumberFromIMaskAction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;

readonly class AuthenticateUserByCredentialsAction
{
    public function __construct(
        private FormatNumberFromIMaskAction $formatNumber,
    )
    {

    }

    public function __invoke(array $data, bool $remember = false): User
    {
        if (Auth::attempt([
            'phone' => ($this->formatNumber)($data['phone']),
            'password' => $data['password']
        ], $remember)) return Auth::user();

        throw new UnauthorizedException();
    }
}
