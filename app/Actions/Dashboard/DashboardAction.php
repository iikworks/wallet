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
                    'sum_expenses_at_this_month' => normalize_number($this->getTransactionsSumAtThisMonth(
                        $accounts,
                        $currency,
                        $now,
                        Transaction::EXPENSE_TYPE,
                    )),
                    'sum_replenishments_at_this_month' => normalize_number($this->getTransactionsSumAtThisMonth(
                        $accounts,
                        $currency,
                        $now,
                        Transaction::REPLENISHMENT_TYPE
                    )),
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
            ->orderBy('date', 'desc')
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

    private function getTransactionsSumAtThisMonth(
        Collection $accounts,
        string     $currency,
        Carbon     $now,
        string     $type
    ): int
    {
        $sum = 0;

        foreach ($accounts as $account) {
            $accountSum = Transaction::query()->where('account_id', $account->id)
                ->whereMonth('created_at', $now->format('m'))
                ->whereYear('created_at', $now->format('Y'))
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