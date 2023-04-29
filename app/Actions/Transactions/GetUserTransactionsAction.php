<?php

namespace App\Actions\Transactions;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

readonly class GetUserTransactionsAction
{
    public function __invoke(User $user, int $page, int $limit = 15): LengthAwarePaginator
    {
        $userAccountsIds = $user->accounts->pluck('id');

        return Transaction::query()
            ->whereIn('account_id', $userAccountsIds)
            ->with(['account.user', 'organization'])
            ->latest('date')
            ->paginate(
                perPage: $limit,
                page: $page,
            );
    }
}
