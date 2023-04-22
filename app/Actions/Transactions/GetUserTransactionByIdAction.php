<?php

namespace App\Actions\Transactions;

use App\Models\Transaction;
use App\Models\User;

readonly class GetUserTransactionByIdAction
{
    public function __invoke(User $user, int $subscriptionId): Transaction|null
    {
        $userAccountsIds = $user->accounts->pluck('id');

        return Transaction::query()
            ->whereIn('account_id', $userAccountsIds)
            ->with(['account.user', 'organization'])
            ->where('id', $subscriptionId)
            ->first();
    }
}
