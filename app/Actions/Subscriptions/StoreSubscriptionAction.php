<?php

namespace App\Actions\Subscriptions;

use App\Models\Subscription;
use App\Models\User;
use InvalidArgumentException;

readonly class StoreSubscriptionAction
{
    public function __invoke(array $data, User $user): Subscription
    {
        $account = $user->accounts()->where('id', $data['account_id'])->first();
        if (!$account) throw new InvalidArgumentException('Account ' . $data['account_id'] . ' not found');

        $data['amount'] = $data['amount'] * 100;

        $subscription = new Subscription();
        $subscription->fill($data);
        $subscription->save();

        return $subscription;
    }
}
