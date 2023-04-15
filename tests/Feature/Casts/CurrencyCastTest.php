<?php

namespace Tests\Feature\Casts;

use App\Models\Account;
use App\Models\Organization;
use App\Models\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Tests\TestCase;

class CurrencyCastTest extends TestCase
{
    use RefreshDatabase;

    public function test_err_currency_must_exist(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $subscription = new Subscription();
        $subscription->account_id = Account::factory()->create()->id;
        $subscription->organization_id = Organization::factory()->create()->id;
        $subscription->amount = -500;
        $subscription->currency = 'wrong';
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

        $this->assertEquals(500, $subscription->amount);
    }
}
