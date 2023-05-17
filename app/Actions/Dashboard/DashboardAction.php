<?php

namespace App\Actions\Dashboard;

use App\Actions\Accounts\ConvertCurrencyAction;
use App\Http\Resources\AccountCollection;
use App\Http\Resources\SubscriptionCollection;
use App\Http\Resources\TransactionCollection;
use App\Http\Resources\TransactionResource;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

readonly class DashboardAction
{
    public function __construct(
        private ConvertCurrencyAction $convertAction,
    )
    {

    }

    public function __invoke(User $user): JsonResponse
    {
        $accounts = $user->accounts;
        $accountsIds = $accounts->pluck('id');
        $currency = config('app.currency');
        $latestTransactions = $this->getLatestTransactions($accountsIds);
        $now = now();

        return response()->json([
            'data' => [
                'has_other_currencies' => $this->hasOtherCurrencies($accounts, $currency),
                'accounts' => [
                    'list' => new AccountCollection($accounts->take(3)),
                    'count' => $accounts->count(),
                ],
                'transactions' => [
                    'latest' => new TransactionCollection($latestTransactions),
                    'latest_first' => $latestTransactions->first() ? new TransactionResource($latestTransactions->first()) : null,
                    'count' => $this->getTransactionsCount($accountsIds),
                    'statistics_by_month' => $this->getTransactionsSumByTypeAndMonth($user->id, $currency),
                ],
                'subscriptions' => [
                    'list' => new SubscriptionCollection($this->filterSubscriptions($this->getSubscriptions($accountsIds), $now)->take(3)),
                    'count' => $this->getSubscriptionsCount($accountsIds),
                ],
            ]
        ]);
    }

    private function getLatestTransactions(Collection $accountsIds): Collection
    {
        return Transaction::query()
            ->whereIn('account_id', $accountsIds)
            ->latest('date')
            ->limit(10)
            ->get();
    }

    private function hasOtherCurrencies(Collection $accounts, string $mainCurrency): bool
    {
        $currencies = $this->getCurrencies();
        $currencies = $currencies->reject(function (string $element) use ($mainCurrency) {
            return $element == $mainCurrency;
        });

        foreach ($currencies as $currency) {
            if ($accounts->where('currency', $currency)
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

    private function getTransactionsCount(Collection $accountsIds): int
    {
        return Transaction::query()
            ->whereIn('account_id', $accountsIds)
            ->count();
    }

    private function getTransactionsSumByTypeAndMonth(int $userId, string $currency): Collection
    {
        $transactionsSumByTypeMonthAndCurrency = Transaction::query()->selectRaw('to_char(date, \'YYYY-MM\') as month, currency as currency, CAST(SUM(CASE WHEN transactions.type = \'' . Transaction::REPLENISHMENT_TYPE . '\' THEN transactions.amount ELSE 0 END) AS BIGINT) as topup_total, CAST(SUM(CASE WHEN transactions.type = \'' . Transaction::EXPENSE_TYPE . '\' THEN transactions.amount ELSE 0 END) AS BIGINT) as withdrawal_total')
            ->join('accounts', 'transactions.account_id', '=', 'accounts.id')
            ->join('users', 'accounts.user_id', '=', 'users.id')
            ->where('users.id', $userId)
            ->groupBy('month')
            ->groupBy('currency')
            ->latest('month')
            ->get();

        $transactionsSumByTypeAndMonth = collect();

        $transactionsSumByTypeMonthAndCurrency->map(function ($value) use ($currency, $transactionsSumByTypeAndMonth) {
            if ($value->currency == $currency) {
                $transactionsSumByTypeAndMonth->add($value);
            } else {
                $transactionsSumByTypeAndMonth->map(function ($valueWithSelectedCurrency) use ($value, $currency) {
                    if ($valueWithSelectedCurrency->month == $value->month) {
                        $valueWithSelectedCurrency->topup_total += ($this->convertAction)($value->currency, $currency, $value->topup_total);
                        $valueWithSelectedCurrency->withdrawal_total += ($this->convertAction)($value->currency, $currency, $value->withdrawal_total);
                    }
                });
            }
        });

        $transactionsSumByTypeAndMonth->map(function ($value) {
            $value->topup_total = normalize_number($value->topup_total);
            $value->withdrawal_total = normalize_number($value->withdrawal_total);
        });

        return $transactionsSumByTypeAndMonth;
    }

    public function filterSubscriptions(Collection $subscriptions, Carbon $now): Collection
    {
        return $subscriptions->sortBy(function (Subscription $subscription) use ($now) {
            return $subscription->daysBeforePayment();
        });
    }

    private function getSubscriptions(Collection $accountsIds): Collection
    {
        return Subscription::query()
            ->whereIn('account_id', $accountsIds)
            ->get();
    }

    private function getSubscriptionsCount(Collection $accountsIds): int
    {
        return Subscription::query()
            ->whereIn('account_id', $accountsIds)
            ->count();
    }
}