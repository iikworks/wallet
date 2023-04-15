<?php

namespace App\Actions\Transactions;

use App\Actions\Accounts\CalculateNewBalanceAction;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

readonly class StoreTransactionAndUpdateAccountBalanceAction
{
    public function __construct(
        private CalculateNewBalanceAction $calculateNewBalance,
    )
    {

    }

    public function __invoke(array $data, User $user): Transaction
    {
        $account = $user->accounts()->where('id', $data['account_id'])->first();
        if (!$account) throw new InvalidArgumentException('Account ' . $data['account_id'] . ' not found');

        $amount = $data['amount'] * 100;
        $account->balance = ($this->calculateNewBalance)($account->balance, $data['type'], $amount);

        $transaction = new Transaction();
        $transaction->account()->associate($account);
        $transaction->organization_id = $data['organization_id'];
        $transaction->type = $data['type'];
        $transaction->amount = $amount;
        $transaction->date = $data['date'];

        DB::transaction(function () use ($account, $transaction) {
            $transaction->save();
            $account->save();
        });

        return $transaction;
    }
}
