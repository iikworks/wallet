<?php

namespace App\Actions\Users;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

readonly class StoreUserAction
{
    public function __construct(
        private FormatNumberFromIMaskAction $formatNumber,
    )
    {

    }

    public function __invoke(array $data): User
    {
        $phone = ($this->formatNumber)($data['phone']);
        if (User::query()->where('phone', $phone)->first())
            throw new UnprocessableEntityHttpException(__('auth.phone_number_taken'));

        $user = new User();
        $user->phone = $phone;
        $user->first_name = $data['first_name'];
        $user->last_name = $data['last_name'];
        $user->password = Hash::make($data['password']);
        $user->save();

        return $user;
    }
}
