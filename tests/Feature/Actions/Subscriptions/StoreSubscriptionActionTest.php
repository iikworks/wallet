<?php

namespace Tests\Feature\Actions\Subscriptions;

use App\Actions\Subscriptions\StoreSubscriptionAction;
use App\Models\Account;
use App\Models\Organization;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Tests\TestCase;

class StoreSubscriptionActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_account_id_must_be_exist_in_user_accounts()
    {
        $this->expectException(InvalidArgumentException::class);
        $account = Account::factory()->create();

        (new StoreSubscriptionAction)(['account_id' => $account->id], User::factory()->create());
    }

    public function test_can_store_a_new_subscription()
    {
        $account = Account::factory()->create();
        $organization = Organization::factory()->create();
        $currency = array_key_first(config('constants.currencies'));

        (new StoreSubscriptionAction)([
            'account_id' => $account->id,
            'organization_id' => $organization->id,
            'amount' => 500,
            'currency' => $currency,
            'day' => 5,
        ], $account->user);

        tap(Subscription::query()->first(), function (Subscription $subscription) use ($account, $organization, $currency) {
            $this->assertEquals($account->id, $subscription->account_id);
            $this->assertEquals($organization->id, $subscription->organization_id);
            $this->assertEquals(50000, $subscription->amount);
            $this->assertEquals($currency, $subscription->currency);
            $this->assertEquals(5, $subscription->day);
        });
    }
}
