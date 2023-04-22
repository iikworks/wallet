<?php

namespace Tests\Feature\Controllers\Dashboard;

use App\Models\Account;
use App\Models\Organization;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Request;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_not_has_other_currencies(): void
    {
        $this->seed();
        $user = User::factory()->create();
        Account::factory()->create([
            'user_id' => $user->id,
            'currency' => config('app.currency'),
        ]);
        Sanctum::actingAs(
            $user,
            ['*']
        );


        $response = $this->json(Request::METHOD_GET, route('dashboard'));
        $response->assertJson([
            'has_other_currencies' => false,
        ]);
    }

    public function test_has_other_currencies(): void
    {
        $this->seed();
        $user = User::factory()->create();
        Account::factory()->create([
            'user_id' => $user->id,
            'currency' => array_key_last(config('constants.currencies')),
        ]);
        Sanctum::actingAs(
            $user,
            ['*']
        );


        $response = $this->json(Request::METHOD_GET, route('dashboard'));
        $response->assertJson([
            'has_other_currencies' => true,
        ]);
    }

    public function test_accounts()
    {
        $this->seed();
        $user = User::factory()->create();
        $account = Account::factory()->create([
            'user_id' => $user->id,
        ]);
        Sanctum::actingAs(
            $user,
            ['*']
        );

        $response = $this->json(Request::METHOD_GET, route('dashboard'));
        $response->assertJson([
            'accounts' => [
                'list' => [
                    [
                        'id' => $account->id,
                    ]
                ],
                'count' => 1,
            ],
        ]);
    }

    public function test_transactions_latest_and_count()
    {
        $this->seed();
        $user = User::factory()->create();
        Organization::factory()->create();
        $account = Account::factory()->create([
            'user_id' => $user->id,
        ]);
        $transaction = Transaction::factory()->create([
            'account_id' => $account->id,
        ]);
        Sanctum::actingAs(
            $user,
            ['*']
        );

        $response = $this->json(Request::METHOD_GET, route('dashboard'));
        $response->assertJson([
            'transactions' => [
                'latest' => [
                    [
                        'id' => $transaction->id,
                    ]
                ],
                'latest_first' => [
                    'id' => $transaction->id,
                ],
                'count' => 1,
            ],
        ]);
    }

    public function test_transactions_sums()
    {
        $this->seed();
        $user = User::factory()->create();
        Organization::factory()->create();
        $account = Account::factory()->create([
            'user_id' => $user->id,
            'currency' => config('app.currency')
        ]);
        $expenseTransactions = Transaction::factory(5)->create([
            'account_id' => $account->id,
            'type' => Transaction::EXPENSE_TYPE,
            'amount' => 500,
            'date' => now(),
        ]);
        $replenishmentTransactions = Transaction::factory(5)->create([
            'account_id' => $account->id,
            'type' => Transaction::REPLENISHMENT_TYPE,
            'amount' => 50000,
            'date' => now(),
        ]);
        Sanctum::actingAs(
            $user,
            ['*']
        );

        $response = $this->json(Request::METHOD_GET, route('dashboard'));
        $response->assertJson([
            'transactions' => [
                'sum_expenses_at_this_month' => $expenseTransactions->sum('amount') / 100,
                'sum_replenishments_at_this_month' => $replenishmentTransactions->sum('amount') / 100,
            ],
        ]);
    }

    public function test_subscriptions()
    {
        $this->seed();
        $user = User::factory()->create();
        Organization::factory()->create();
        $account = Account::factory()->create([
            'user_id' => $user->id,
        ]);
        $subscription = Subscription::factory()->create([
            'account_id' => $account->id,
        ]);
        Sanctum::actingAs(
            $user,
            ['*']
        );

        $response = $this->json(Request::METHOD_GET, route('dashboard'));
        $response->assertJson([
            'subscriptions' => [
                'list' => [
                    [
                        'id' => $subscription->id,
                    ]
                ],
                'count' => 1,
            ],
        ]);
    }
}
