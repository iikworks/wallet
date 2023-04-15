<?php

namespace Tests\Feature\Casts;

use App\Models\Account;
use App\Models\Organization;
use App\Models\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Tests\TestCase;

class SubscriptionDayCastTest extends TestCase
{
    use RefreshDatabase;

    public function test_err_day_must_be_greater_than_zero(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $subscription = new Subscription();
        $subscription->account_id = Account::factory()->create()->id;
        $subscription->organization_id = Organization::factory()->create()->id;
        $subscription->day = 0;
    }

    public function test_err_day_must_be_less_than_32(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $subscription = new Subscription();
        $subscription->account_id = Account::factory()->create()->id;
        $subscription->organization_id = Organization::factory()->create()->id;
        $subscription->day = 35;
    }

    public function test_no_err(): void
    {
        $subscription = new Subscription();
        $subscription->account_id = Account::factory()->create()->id;
        $subscription->organization_id = Organization::factory()->create()->id;
        $subscription->amount = 500;
        $subscription->currency = array_key_first(config('constants.currencies'));
        $subscription->day = 5;
        $subscription->save();

        $this->assertEquals(5, $subscription->day);
    }
}
