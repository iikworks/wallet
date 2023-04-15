<?php

namespace Tests\Feature\Casts;

use App\Models\Account;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Tests\TestCase;

class AccountBalanceCastTest extends TestCase
{
    use RefreshDatabase;

    public function test_err_balance_must_be_greater_than_zero(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $account = new Account();
        $account->balance = -500;
    }

    public function test_no_err(): void
    {
        $account = new Account();
        $account->user_id = User::factory()->create()->id;
        $account->balance = 500;
        $account->currency = array_key_first(config('constants.currencies'));
        $account->details = null;
        $account->save();

        $this->assertEquals(500, $account->balance);
    }
}
