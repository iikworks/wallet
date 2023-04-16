<?php

namespace App\View\Transactions;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

class ListView
{
    public function __invoke(User $user, int $page): View|Application|Factory
    {
        $accountsIds = $user->accounts->pluck('id');
        $transactions = Transaction::query()
            ->whereIn('account_id', $accountsIds)
            ->latest('date')
            ->paginate(50, page: $page);

        return view('transactions.list', [
            'title' => __('accounts.title'),
            'transactions' => $transactions,
        ]);
    }
}
