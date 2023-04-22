<?php

namespace App\Actions\Subscriptions;

use App\Models\Subscription;
use App\Models\User;

readonly class GetUserSubscriptionByIdAction
{
    public function __invoke(User $user, int $subscriptionId): Subscription|null
    {
        $userAccountsIds = $user->accounts->pluck('id');

        return Subscription::query()
            ->whereIn('account_id', $userAccountsIds)
            ->with(['account.user', 'organization'])
            ->where('id', $subscriptionId)
            ->first();
    }
}
