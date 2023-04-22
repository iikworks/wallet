<?php

namespace App\Actions\Subscriptions;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

readonly class GetUserSubscriptionsAction
{
    public function __invoke(User $user, int $page, int $limit = 15): LengthAwarePaginator
    {
        $userAccountsIds = $user->accounts->pluck('id');

        return Subscription::query()
            ->whereIn('account_id', $userAccountsIds)
            ->with(['account.user', 'organization'])
            ->paginate(
                perPage: $limit,
                page: $page,
            );
    }
}
