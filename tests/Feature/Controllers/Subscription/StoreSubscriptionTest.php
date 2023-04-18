<?php

namespace Tests\Feature\Controllers\Subscription;

use App\Models\Account;
use App\Models\Organization;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreSubscriptionTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_cant_store_a_new_subscription(): void
    {
        $response = $this->post(route('subscriptions'));
        $response->assertRedirectToRoute('login');
        $this->assertEquals(0, Subscription::query()->count());
    }

    public function test_can_store_a_new_subscription(): void
    {
        $account = Account::factory()->create();
        $organization = Organization::factory()->create();
        $currency = array_key_first(config('constants.currencies'));

        $response = $this->actingAs($account->user)->post(route('subscriptions'), [
            'account_id' => $account->id,
            'organization_id' => $organization->id,
            'currency' => $currency,
            'amount' => 500,
            'day' => 5,
        ]);
        $response->assertRedirectToRoute('subscriptions');

        $this->assertEquals(1, Subscription::query()->count());
        tap(Subscription::query()->first(), function (Subscription $subscription) use ($account, $organization, $currency) {
            $this->assertEquals($account->id, $subscription->account_id);
            $this->assertEquals($organization->id, $subscription->organization_id);
            $this->assertEquals($currency, $subscription->currency);
            $this->assertEquals(50000, $subscription->amount);
            $this->assertEquals(5, $subscription->day);
        });
    }

    public function test_account_id_field_required_to_store_a_new_subscription()
    {
        $user = User::factory()->create();
//        $account = Account::factory()->create([
//            'balance' => 500000,
//            'user_id' => $user->id,
//        ]);
        $organization = Organization::factory()->create();
        $currency = array_key_first(config('constants.currencies'));

        $response = $this->actingAs($user)->post(route('subscriptions'), [
//            'account_id' => $account->id,
            'organization_id' => $organization->id,
            'type' => $currency,
            'amount' => 500,
            'date' => 5,
        ]);

        $response->assertSessionHasErrorsIn('account_id');
        $this->assertEquals(0, Subscription::query()->count());
    }

    public function test_account_id_must_be_exist_at_user_to_store_a_new_subscription()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $account = Account::factory()->create([
            'balance' => 500000,
            'user_id' => $otherUser->id,
        ]);
        $organization = Organization::factory()->create();
        $currency = array_key_first(config('constants.currencies'));

        $response = $this->actingAs($user)->post(route('subscriptions'), [
            'account_id' => $account->id,
            'organization_id' => $organization->id,
            'currency' => $currency,
            'amount' => 500,
            'day' => 5,
        ]);

        $response->assertSessionHasErrorsIn('account_id');
        $this->assertEquals(0, Subscription::query()->count());
    }

    public function test_organization_id_field_required_to_store_a_new_subscription()
    {
        $user = User::factory()->create();
        $account = Account::factory()->create([
            'balance' => 500000,
            'user_id' => $user->id,
        ]);
//        $organization = Organization::factory()->create();
        $currency = array_key_first(config('constants.currencies'));

        $response = $this->actingAs($user)->post(route('subscriptions'), [
            'account_id' => $account->id,
//            'organization_id' => $organization->id,
            'currency' => $currency,
            'amount' => 500,
            'day' => 5,
        ]);

        $response->assertSessionHasErrorsIn('organization_id');
        $this->assertEquals(0, Subscription::query()->count());
    }

    public function test_organization_id_must_be_exist_to_store_a_new_subscription()
    {
        $user = User::factory()->create();
        $account = Account::factory()->create([
            'balance' => 500000,
            'user_id' => $user->id,
        ]);
//        $organization = Organization::factory()->create();
        $currency = array_key_first(config('constants.currencies'));

        $response = $this->actingAs($user)->post(route('transactions'), [
            'account_id' => $account->id,
            'organization_id' => 500,
            'currency' => $currency,
            'amount' => 500,
            'day' => 5,
        ]);

        $response->assertSessionHasErrorsIn('organization_id');
        $this->assertEquals(0, Subscription::query()->count());
    }

    public function test_amount_field_required_to_store_a_new_subscription()
    {
        $user = User::factory()->create();
        $account = Account::factory()->create([
            'balance' => 500000,
            'user_id' => $user->id,
        ]);
        $organization = Organization::factory()->create();
        $currency = array_key_first(config('constants.currencies'));

        $response = $this->actingAs($user)->post(route('subscriptions'), [
            'account_id' => $account->id,
            'organization_id' => $organization->id,
            'currency' => $currency,
//            'amount' => 500,
            'day' => 5,
        ]);

        $response->assertSessionHasErrorsIn('amount');
        $this->assertEquals(0, Subscription::query()->count());
    }

    public function test_currency_field_required_to_store_a_new_subscription()
    {
        $user = User::factory()->create();
        $account = Account::factory()->create([
            'balance' => 500000,
            'user_id' => $user->id,
        ]);
        $organization = Organization::factory()->create();
//        $currency = array_key_first(config('constants.currencies'));

        $response = $this->actingAs($user)->post(route('subscriptions'), [
            'account_id' => $account->id,
            'organization_id' => $organization->id,
//            'currency' => $currency,
            'amount' => 500,
            'day' => 5,
        ]);

        $response->assertSessionHasErrorsIn('currency');
        $this->assertEquals(0, Subscription::query()->count());
    }

    public function test_currency_field_must_be_valid_to_store_a_new_subscription()
    {
        $user = User::factory()->create();
        $account = Account::factory()->create([
            'balance' => 500000,
            'user_id' => $user->id,
        ]);
        $organization = Organization::factory()->create();
//        $currency = array_key_first(config('constants.currencies'));

        $response = $this->actingAs($user)->post(route('subscriptions'), [
            'account_id' => $account->id,
            'organization_id' => $organization->id,
            'currency' => 'bad',
            'amount' => 500,
            'day' => 5,
        ]);

        $response->assertSessionHasErrorsIn('currency');
        $this->assertEquals(0, Subscription::query()->count());
    }

    public function test_amount_field_must_be_numeric_to_store_a_new_subscription()
    {
        $user = User::factory()->create();
        $account = Account::factory()->create([
            'balance' => 500000,
            'user_id' => $user->id,
        ]);
        $organization = Organization::factory()->create();
        $currency = array_key_first(config('constants.currencies'));

        $response = $this->actingAs($user)->post(route('subscriptions'), [
            'account_id' => $account->id,
            'organization_id' => $organization->id,
            'currency' => $currency,
            'amount' => 'no numeric',
            'day' => 5,
        ]);

        $response->assertSessionHasErrorsIn('amount');
        $this->assertEquals(0, Subscription::query()->count());
    }

    public function test_amount_field_must_be_positive_or_zero_to_store_a_new_subscription()
    {
        $user = User::factory()->create();
        $account = Account::factory()->create([
            'balance' => 500000,
            'user_id' => $user->id,
        ]);
        $organization = Organization::factory()->create();
        $currency = array_key_first(config('constants.currencies'));

        $response = $this->actingAs($user)->post(route('subscriptions'), [
            'account_id' => $account->id,
            'organization_id' => $organization->id,
            'currency' => $currency,
            'amount' => -500,
            'day' => 5,
        ]);

        $response->assertSessionHasErrorsIn('amount');
        $this->assertEquals(0, Subscription::query()->count());
    }

    public function test_day_field_required_to_store_a_new_subscription()
    {
        $user = User::factory()->create();
        $account = Account::factory()->create([
            'balance' => 500000,
            'user_id' => $user->id,
        ]);
        $organization = Organization::factory()->create();
        $currency = array_key_first(config('constants.currencies'));

        $response = $this->actingAs($user)->post(route('subscriptions'), [
            'account_id' => $account->id,
            'organization_id' => $organization->id,
            'currency' => $currency,
            'amount' => 500,
//            'day' => 5,
        ]);

        $response->assertSessionHasErrorsIn('day');
        $this->assertEquals(0, Subscription::query()->count());
    }

    public function test_day_field_must_be_numeric_to_store_a_new_subscription()
    {
        $user = User::factory()->create();
        $account = Account::factory()->create([
            'balance' => 500000,
            'user_id' => $user->id,
        ]);
        $organization = Organization::factory()->create();
        $currency = array_key_first(config('constants.currencies'));

        $response = $this->actingAs($user)->post(route('subscriptions'), [
            'account_id' => $account->id,
            'organization_id' => $organization->id,
            'currency' => $currency,
            'amount' => 500,
            'day' => 'not numeric',
        ]);

        $response->assertSessionHasErrorsIn('day');
        $this->assertEquals(0, Subscription::query()->count());
    }

    public function test_day_field_must_be_more_than_0_to_store_a_new_subscription()
    {
        $user = User::factory()->create();
        $account = Account::factory()->create([
            'balance' => 500000,
            'user_id' => $user->id,
        ]);
        $organization = Organization::factory()->create();
        $currency = array_key_first(config('constants.currencies'));

        $response = $this->actingAs($user)->post(route('subscriptions'), [
            'account_id' => $account->id,
            'organization_id' => $organization->id,
            'currency' => $currency,
            'amount' => 500,
            'day' => 0,
        ]);

        $response->assertSessionHasErrorsIn('day');
        $this->assertEquals(0, Subscription::query()->count());
    }

    public function test_day_field_must_be_less_than_0_to_store_a_new_subscription()
    {
        $user = User::factory()->create();
        $account = Account::factory()->create([
            'balance' => 500000,
            'user_id' => $user->id,
        ]);
        $organization = Organization::factory()->create();
        $currency = array_key_first(config('constants.currencies'));

        $response = $this->actingAs($user)->post(route('subscriptions'), [
            'account_id' => $account->id,
            'organization_id' => $organization->id,
            'currency' => $currency,
            'amount' => 500,
            'day' => 32,
        ]);

        $response->assertSessionHasErrorsIn('day');
        $this->assertEquals(0, Subscription::query()->count());
    }
}
