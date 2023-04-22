<?php

namespace App\Actions\Auth;

use App\Actions\Users\FormatNumberFromIMaskAction;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\UnauthorizedException;

readonly class AuthenticateUserByCredentialsAction
{
    public function __construct(
        private FormatNumberFromIMaskAction $formatNumber,
        private AuthenticateUserAction      $authenticateUser,
    )
    {

    }

    public function __invoke(array $data): array
    {
        $user = User::query()->where('phone', ($this->formatNumber)($data['phone']))->first();
        if (!$user) throw new UnauthorizedException();
        if (!Hash::check($data['password'], $user->password)) throw new UnauthorizedException();

        return [
            'access_token' => ($this->authenticateUser)($user),
            'user' => $user,
        ];
    }
}
