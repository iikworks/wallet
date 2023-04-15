<?php

namespace Tests\Feature\Actions\Transactions;

use App\Actions\Accounts\CalculateNewBalanceAction;
use App\Actions\Transactions\StoreTransactionAndUpdateAccountBalanceAction;
use App\Models\Account;
use App\Models\Organization;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Tests\TestCase;

class StoreTransactionAndUpdateAccountBalanceActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_account_id_must_be_exist_in_user_accounts(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $account = Account::factory()->create();

        (new StoreTransactionAndUpdateAccountBalanceAction(
            new CalculateNewBalanceAction(),
        ))(['account_id' => $account->id], User::factory()->create());
    }

    public function test_update_account_balance_err(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $user = User::factory()->create();

        (new StoreTransactionAndUpdateAccountBalanceAction(
            new CalculateNewBalanceAction(),
        ))([
            'account_id' => Account::factory()->create([
                'user_id' => $user->id,
            ])->id,
            'organization_id' => Organization::factory()->create()->id,
            'type' => Transaction::REPLENISHMENT_TYPE,
            'amount' => 0,
            'date' => Carbon::now(),
        ], $user);
    }

    public function test_expense_no_err()
    {
        $user = User::factory()->create();
        $account = Account::factory()->create([
            'balance' => 500000,
            'user_id' => $user->id,
        ]);
        $organization = Organization::factory()->create();
        $date = Carbon::make(fake()->dateTimeBetween('-3 months'));

        (new StoreTransactionAndUpdateAccountBalanceAction(
            new CalculateNewBalanceAction(),
        ))([
            'account_id' => $account->id,
            'organization_id' => $organization->id,
            'type' => Transaction::EXPENSE_TYPE,
            'amount' => 500,
            'date' => $date,
        ], $user);

        tap(Transaction::query()->first(), function (Transaction $transaction) use ($account, $organization, $date) {
            $this->assertEquals($account->id, $transaction->account->id);
            $this->assertEquals($organization->id, $transaction->organization->id);
            $this->assertEquals(Transaction::EXPENSE_TYPE, $transaction->type);
            $this->assertEquals(50000, $transaction->amount);
            $this->assertEquals($date, $transaction->date);

            tap(Account::query()->find($account->id), function (Account $account) {
                $this->assertEquals(450000, $account->balance);
            });
        });
    }

    public function test_replenishment_no_err()
    {
        $user = User::factory()->create();
        $account = Account::factory()->create([
            'balance' => 5000,
            'user_id' => $user->id,
        ]);
        $organization = Organization::factory()->create();
        $date = Carbon::make(fake()->dateTimeBetween('-3 months'));

        (new StoreTransactionAndUpdateAccountBalanceAction(
            new CalculateNewBalanceAction(),
        ))([
            'account_id' => $account->id,
            'organization_id' => $organization->id,
            'type' => Transaction::REPLENISHMENT_TYPE,
            'amount' => 500,
            'date' => $date,
        ], $user);

        tap(Transaction::query()->first(), function (Transaction $transaction) use ($account, $organization, $date) {
            $this->assertEquals($account->id, $transaction->account->id);
            $this->assertEquals($organization->id, $transaction->organization->id);
            $this->assertEquals(Transaction::REPLENISHMENT_TYPE, $transaction->type);
            $this->assertEquals(50000, $transaction->amount);
            $this->assertEquals($date, $transaction->date);

            tap(Account::query()->find($account->id), function (Account $account) {
                $this->assertEquals(55000, $account->balance);
            });
        });
    }
}
