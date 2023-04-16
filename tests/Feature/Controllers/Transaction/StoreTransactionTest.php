<?php

namespace Tests\Feature\Controllers\Transaction;

use App\Models\Account;
use App\Models\Organization;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreTransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_cant_store_a_new_account(): void
    {
        $response = $this->post(route('transactions'));
        $response->assertRedirectToRoute('login');
        $this->assertEquals(0, Account::query()->count());
    }

    public function test_can_store_a_new_expense_transaction(): void
    {
        $user = User::factory()->create();
        $account = Account::factory()->create([
            'balance' => 500000,
            'user_id' => $user->id,
        ]);
        $organization = Organization::factory()->create();
        $date = Carbon::make(fake()->dateTimeBetween('-3 months'));

        $response = $this->actingAs($user)->post(route('transactions'), [
            'account_id' => $account->id,
            'organization_id' => $organization->id,
            'type' => Transaction::EXPENSE_TYPE,
            'amount' => 500,
            'date' => $date->format('Y-m-d\TH:i'),
        ]);
        $response->assertRedirectToRoute('transactions');

        $this->assertEquals(1, Transaction::query()->count());
        tap(Transaction::query()->first(), function (Transaction $transaction) use ($account, $organization, $date) {
            $this->assertEquals($account->id, $transaction->account->id);
            $this->assertEquals($organization->id, $transaction->organization->id);
            $this->assertEquals(Transaction::EXPENSE_TYPE, $transaction->type);
            $this->assertEquals(50000, $transaction->amount);
            $this->assertEquals($date->format('Y-m-d\TH:i'), $transaction->date->format('Y-m-d\TH:i'));

            tap(Account::query()->find($account->id), function (Account $account) {
                $this->assertEquals(450000, $account->balance);
            });
        });
    }

    public function test_can_store_a_new_replenishment_transaction(): void
    {
        $user = User::factory()->create();
        $account = Account::factory()->create([
            'balance' => 500000,
            'user_id' => $user->id,
        ]);
        $organization = Organization::factory()->create();
        $date = Carbon::make(fake()->dateTimeBetween('-3 months'));

        $response = $this->actingAs($user)->post(route('transactions'), [
            'account_id' => $account->id,
            'organization_id' => $organization->id,
            'type' => Transaction::REPLENISHMENT_TYPE,
            'amount' => 500,
            'date' => $date->format('Y-m-d\TH:i'),
        ]);
        $response->assertRedirectToRoute('transactions');

        $this->assertEquals(1, Transaction::query()->count());
        tap(Transaction::query()->first(), function (Transaction $transaction) use ($account, $organization, $date) {
            $this->assertEquals($account->id, $transaction->account->id);
            $this->assertEquals($organization->id, $transaction->organization->id);
            $this->assertEquals(Transaction::REPLENISHMENT_TYPE, $transaction->type);
            $this->assertEquals(50000, $transaction->amount);
            $this->assertEquals($date->format('Y-m-d\TH:i'), $transaction->date->format('Y-m-d\TH:i'));

            tap(Account::query()->find($account->id), function (Account $account) {
                $this->assertEquals(550000, $account->balance);
            });
        });
    }

    public function test_account_id_field_required_to_store_a_new_transaction()
    {
        $user = User::factory()->create();
//        $account = Account::factory()->create([
//            'balance' => 500000,
//            'user_id' => $user->id,
//        ]);
        $organization = Organization::factory()->create();
        $date = Carbon::make(fake()->dateTimeBetween('-3 months'));

        $response = $this->actingAs($user)->post(route('transactions'), [
//            'account_id' => $account->id,
            'organization_id' => $organization->id,
            'type' => Transaction::REPLENISHMENT_TYPE,
            'amount' => 500,
            'date' => $date->format('Y-m-d\TH:i'),
        ]);

        $response->assertSessionHasErrorsIn('account_id');
        $this->assertEquals(0, Transaction::query()->count());
    }

    public function test_account_id_must_be_exist_at_user_to_store_a_new_transaction()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $account = Account::factory()->create([
            'balance' => 500000,
            'user_id' => $otherUser->id,
        ]);
        $organization = Organization::factory()->create();
        $date = Carbon::make(fake()->dateTimeBetween('-3 months'));

        $response = $this->actingAs($user)->post(route('transactions'), [
            'account_id' => $account->id,
            'organization_id' => $organization->id,
            'type' => Transaction::REPLENISHMENT_TYPE,
            'amount' => 500,
            'date' => $date->format('Y-m-d\TH:i'),
        ]);

        $response->assertSessionHasErrorsIn('account_id');
        $this->assertEquals(0, Transaction::query()->count());
    }

    public function test_organization_id_field_required_to_store_a_new_transaction()
    {
        $user = User::factory()->create();
        $account = Account::factory()->create([
            'balance' => 500000,
            'user_id' => $user->id,
        ]);
//        $organization = Organization::factory()->create();
        $date = Carbon::make(fake()->dateTimeBetween('-3 months'));

        $response = $this->actingAs($user)->post(route('transactions'), [
            'account_id' => $account->id,
//            'organization_id' => $organization->id,
            'type' => Transaction::REPLENISHMENT_TYPE,
            'amount' => 500,
            'date' => $date->format('Y-m-d\TH:i'),
        ]);

        $response->assertSessionHasErrorsIn('organization_id');
        $this->assertEquals(0, Transaction::query()->count());
    }

    public function test_organization_id_must_be_exist_to_store_a_new_transaction()
    {
        $user = User::factory()->create();
        $account = Account::factory()->create([
            'balance' => 500000,
            'user_id' => $user->id,
        ]);
//        $organization = Organization::factory()->create();
        $date = Carbon::make(fake()->dateTimeBetween('-3 months'));

        $response = $this->actingAs($user)->post(route('transactions'), [
            'account_id' => $account->id,
            'organization_id' => 500,
            'type' => Transaction::REPLENISHMENT_TYPE,
            'amount' => 500,
            'date' => $date->format('Y-m-d\TH:i'),
        ]);

        $response->assertSessionHasErrorsIn('organization_id');
        $this->assertEquals(0, Transaction::query()->count());
    }

    public function test_amount_field_required_to_store_a_new_transaction()
    {
        $user = User::factory()->create();
        $account = Account::factory()->create([
            'balance' => 500000,
            'user_id' => $user->id,
        ]);
        $organization = Organization::factory()->create();
        $date = Carbon::make(fake()->dateTimeBetween('-3 months'));

        $response = $this->actingAs($user)->post(route('transactions'), [
            'account_id' => $account->id,
            'organization_id' => $organization->id,
            'type' => Transaction::REPLENISHMENT_TYPE,
//            'amount' => 500,
            'date' => $date->format('Y-m-d\TH:i'),
        ]);

        $response->assertSessionHasErrorsIn('amount');
        $this->assertEquals(0, Transaction::query()->count());
    }

    public function test_amount_field_must_be_numeric_to_store_a_new_transaction()
    {
        $user = User::factory()->create();
        $account = Account::factory()->create([
            'balance' => 500000,
            'user_id' => $user->id,
        ]);
        $organization = Organization::factory()->create();
        $date = Carbon::make(fake()->dateTimeBetween('-3 months'));

        $response = $this->actingAs($user)->post(route('transactions'), [
            'account_id' => $account->id,
            'organization_id' => $organization->id,
            'type' => Transaction::REPLENISHMENT_TYPE,
            'amount' => 'no numeric',
            'date' => $date->format('Y-m-d\TH:i'),
        ]);

        $response->assertSessionHasErrorsIn('amount');
        $this->assertEquals(0, Transaction::query()->count());
    }

    public function test_amount_field_must_be_positive_or_zero_to_store_a_new_transaction()
    {
        $user = User::factory()->create();
        $account = Account::factory()->create([
            'balance' => 500000,
            'user_id' => $user->id,
        ]);
        $organization = Organization::factory()->create();
        $date = Carbon::make(fake()->dateTimeBetween('-3 months'));

        $response = $this->actingAs($user)->post(route('transactions'), [
            'account_id' => $account->id,
            'organization_id' => $organization->id,
            'type' => Transaction::REPLENISHMENT_TYPE,
            'amount' => -500,
            'date' => $date->format('Y-m-d\TH:i'),
        ]);

        $response->assertSessionHasErrorsIn('amount');
        $this->assertEquals(0, Transaction::query()->count());
    }

    public function test_type_field_required_to_store_a_new_transaction()
    {
        $user = User::factory()->create();
        $account = Account::factory()->create([
            'balance' => 500000,
            'user_id' => $user->id,
        ]);
        $organization = Organization::factory()->create();
        $date = Carbon::make(fake()->dateTimeBetween('-3 months'));

        $response = $this->actingAs($user)->post(route('transactions'), [
            'account_id' => $account->id,
            'organization_id' => $organization->id,
//            'type' => Transaction::REPLENISHMENT_TYPE,
            'amount' => 500,
            'date' => $date->format('Y-m-d\TH:i'),
        ]);

        $response->assertSessionHasErrorsIn('amount');
        $this->assertEquals(0, Transaction::query()->count());
    }

    public function test_type_field_must_be_valid_to_store_a_new_transaction()
    {
        $user = User::factory()->create();
        $account = Account::factory()->create([
            'balance' => 500000,
            'user_id' => $user->id,
        ]);
        $organization = Organization::factory()->create();
        $date = Carbon::make(fake()->dateTimeBetween('-3 months'));

        $response = $this->actingAs($user)->post(route('transactions'), [
            'account_id' => $account->id,
            'organization_id' => $organization->id,
            'type' => 'invalid',
            'amount' => 500,
            'date' => $date->format('Y-m-d\TH:i'),
        ]);

        $response->assertSessionHasErrorsIn('amount');
        $this->assertEquals(0, Transaction::query()->count());
    }

    public function test_date_field_required_to_store_a_new_transaction()
    {
        $user = User::factory()->create();
        $account = Account::factory()->create([
            'balance' => 500000,
            'user_id' => $user->id,
        ]);
        $organization = Organization::factory()->create();

        $response = $this->actingAs($user)->post(route('transactions'), [
            'account_id' => $account->id,
            'organization_id' => $organization->id,
            'type' => Transaction::REPLENISHMENT_TYPE,
            'amount' => 500,
//            'date' => $date->format('Y-m-d\TH:i'),
        ]);

        $response->assertSessionHasErrorsIn('amount');
        $this->assertEquals(0, Transaction::query()->count());
    }

    public function test_date_field_must_be_valid_to_store_a_new_transaction()
    {
        $user = User::factory()->create();
        $account = Account::factory()->create([
            'balance' => 500000,
            'user_id' => $user->id,
        ]);
        $organization = Organization::factory()->create();

        $response = $this->actingAs($user)->post(route('transactions'), [
            'account_id' => $account->id,
            'organization_id' => $organization->id,
            'type' => Transaction::REPLENISHMENT_TYPE,
            'amount' => 500,
            'date' => 'invalid',
        ]);

        $response->assertSessionHasErrorsIn('amount');
        $this->assertEquals(0, Transaction::query()->count());
    }
}
