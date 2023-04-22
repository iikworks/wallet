<?php

namespace App\Actions\Users;

use App\Actions\Accounts\ConvertCurrencyAction;
use Illuminate\Support\Collection;

readonly class CalculateAccountsBalanceAction
{
    public function __construct(
        private ConvertCurrencyAction $convertAction,
    )
    {

    }

    public function __invoke(Collection $accounts): int
    {
        $mainCurrency = config('app.currency');
        $currencies = collect(array_keys(config('constants.currencies')));
        $balance = 0;

        foreach ($currencies as $currency) {
            $accountBalance = $accounts->where('currency', $currency)
                ->sum('balance');
            if ($currency == $mainCurrency) {
                $balance += $accountBalance;
                continue;
            }

            $balance += ($this->convertAction)($currency, $mainCurrency, $accountBalance);
        }

        return $balance;
    }
}