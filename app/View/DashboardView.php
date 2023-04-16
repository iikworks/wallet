<?php

namespace App\View;

use App\Actions\Accounts\ConvertCurrencyAction;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;

readonly class DashboardView
{
    public function __construct(
        private ConvertCurrencyAction $convertAction,
    )
    {

    }

    public function __invoke(User $user): View|Factory|Application
    {
        $user->load(['accounts', 'accounts.subscriptions', 'accounts.transactions']);

        $latestTransactions = $this->getLatestTransactions($user);
        $currency = config('app.currency');
        $hasOtherCurrencies = $this->hasOtherCurrencies($user, $currency);
        $now = now();

        return view('dashboard', [
            'title' => __('main.wallet'),
            'user' => $user,
            'currency' => $currency,
            'hasOtherCurrencies' => $hasOtherCurrencies,
            'balance' => $this->getBalance($user, $currency),
            'accounts' => $user->accounts->take(3),
            'accountsCount' => $user->accounts->count(),
            'latestTransactions' => $latestTransactions,
            'latestTransaction' => $latestTransactions->first(),
            'transactionsCount' => $this->getTransactionsCount($user),
            'subscriptions' => $this->getSubscriptions($user),
            'subscriptionsCount' => $this->getSubscriptionsCount($user),
            'expensesAtThisMonth' => $this->getTransactionsSumAtThisMonth(
                $user,
                $currency,
                $now,
                Transaction::EXPENSE_TYPE,
            ),
            'replenishmentsAtThisMonth' => $this->getTransactionsSumAtThisMonth(
                $user,
                $currency,
                $now,
                Transaction::REPLENISHMENT_TYPE
            ),
        ]);
    }

    private function getLatestTransactions(User $user): Collection
    {
        $transactions = collect();

        foreach ($user->accounts as $account) {
            $transactions = $transactions->merge($account->transactions);
        }

        return $transactions->sortByDesc(function ($item) {
            return $item->date;
        })->take(10);
    }

    private function hasOtherCurrencies(User $user, string $mainCurrency): bool
    {
        $currencies = $this->getCurrencies();
        $currencies = $currencies->reject(function (string $element) use ($mainCurrency) {
            return $element == $mainCurrency;
        });

        foreach ($currencies as $currency) {
            if ($user->accounts
                    ->where('currency', $currency)
                    ->count() > 0) {
                return true;
            }
        }

        return false;
    }

    private function getCurrencies(): Collection
    {
        return collect(array_keys(config('constants.currencies')));
    }

    private function getBalance(User $user, string $defaultCurrency): int
    {
        $currencies = $this->getCurrencies();
        $balance = 0;

        foreach ($currencies as $currency) {
            $accountBalance = $user->accounts
                ->where('currency', $currency)
                ->sum('balance');
            if ($currency == $defaultCurrency) {
                $balance += $accountBalance;
                continue;
            }

            $balance += ($this->convertAction)($currency, $defaultCurrency, $accountBalance);
        }

        return $balance;
    }

    private function getTransactionsCount(User $user): int
    {
        $transactionsCount = 0;

        foreach ($user->accounts as $account) {
            $transactionsCount += $account->transactions->count();
        }

        return $transactionsCount;
    }

    private function getSubscriptions(User $user): Collection
    {
        $subscriptions = collect();

        foreach ($user->accounts as $account) {
            $subscriptions = $subscriptions->merge($account->subscriptions);
        }

        return $subscriptions->sortByDesc(function ($item) {
            return $item->created_at;
        })->take(3);
    }

    private function getSubscriptionsCount(User $user): int
    {
        $subscriptionsCount = 0;

        foreach ($user->accounts as $account) {
            $subscriptionsCount += $account->subscriptions->count();
        }

        return $subscriptionsCount;
    }

    private function getTransactionsSumAtThisMonth(
        User   $user,
        string $currency,
        Carbon $now,
        string $type
    ): int
    {
        $sum = 0;

        foreach ($user->accounts as $account) {
            $accountSum = $account->transactions
                ->where(function (Transaction $transaction) use ($now) {
                    return $transaction->date->format('Y') == $now->format('Y');
                })
                ->where(function (Transaction $transaction) use ($now) {
                    return $transaction->date->format('m') == $now->format('m');
                })
                ->where('type', $type)
                ->sum('amount');

            if ($account->currency == $currency) {
                $sum += $accountSum;
                continue;
            }

            $sum += ($this->convertAction)($account->currency, $currency, $accountSum);
        }

        return $sum;
    }
}
