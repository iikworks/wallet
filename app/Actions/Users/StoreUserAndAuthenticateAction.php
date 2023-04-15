<?php

namespace App\Actions\Users;

use App\Actions\Auth\AuthenticateUserAction;
use App\Models\User;

readonly class StoreUserAndAuthenticateAction
{
    public function __construct(
        private StoreUserAction        $storeUser,
        private AuthenticateUserAction $authenticateUser,
    )
    {

    }

    public function __invoke(array $data, bool $remember = false): User
    {
        $user = ($this->storeUser)($data);
        ($this->authenticateUser)($user, $remember);

        return $user;
    }
}
