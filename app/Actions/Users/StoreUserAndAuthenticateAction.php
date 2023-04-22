<?php

namespace App\Actions\Users;

use App\Actions\Auth\AuthenticateUserAction;

readonly class StoreUserAndAuthenticateAction
{
    public function __construct(
        private StoreUserAction        $storeUser,
        private AuthenticateUserAction $authenticateUser,
    )
    {

    }

    public function __invoke(array $data): array
    {
        $user = ($this->storeUser)($data);
        return [
            'access_token' => ($this->authenticateUser)($user),
            'user' => $user,
        ];
    }
}
