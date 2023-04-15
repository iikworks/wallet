<?php

namespace Tests\Feature\Actions\Accounts;

use App\Actions\Accounts\StoreAccountAction;
use App\Models\Account;
use App\Models\Bank;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreAccountActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_wrong_bank_id(): void
    {
        $this->expectException(ModelNotFoundException::class);

        (new StoreAccountAction)(1, [
            'balance' => 500,
            'currency' => array_keys(config('constants.currencies'))[0],
            'type' => Account::BANK_ACCOUNT_TYPE,
            'details' => [
                'account_number' => 'NUMBER',
                'bank_id' => 1,
            ],
        ]);
        $this->assertEquals(0, Account::query()->count());
    }

    public function test_can_store_a_new_cash_account(): void
    {
        $user = User::factory()->create();
        $currency = array_keys(config('constants.currencies'))[0];

        (new StoreAccountAction)($user->id, [
            'balance' => 5000,
            'currency' => $currency,
            'type' => Account::CASH_TYPE,
        ]);

        $this->assertEquals(1, Account::query()->count());
        tap(Account::query()->first(), function (Account $account) use ($user, $currency) {
            $this->assertEquals($user->id, $account->user_id);
            $this->assertEquals(500000, $account->balance);
            $this->assertEquals($currency, $account->currency);
        });
    }

    public function test_can_store_a_new_bank_account(): void
    {
        $user = User::factory()->create();
        $bank = Bank::factory()->create();
        $currency = array_keys(config('constants.currencies'))[0];

        (new StoreAccountAction)($user->id, [
            'balance' => 5000,
            'currency' => $currency,
            'type' => Account::BANK_ACCOUNT_TYPE,
            'details' => [
                'account_number' => 'NUMBER',
                'bank_id' => $bank->id,
            ],
        ]);

        $this->assertEquals(1, Account::query()->count());
        tap(Account::query()->first(), function (Account $account) use ($user, $currency, $bank) {
            $this->assertEquals($user->id, $account->user_id);
            $this->assertEquals(500000, $account->balance);
            $this->assertEquals($currency, $account->currency);
            $this->assertEquals('NUMBER', $account->details->getNumber());
            $this->assertEquals($bank->id, $account->details->getBank()->id);
        });
    }

    public function test_can_store_a_new_card_account(): void
    {
        $user = User::factory()->create();
        $bank = Bank::factory()->create();
        $currency = array_keys(config('constants.currencies'))[0];
        $system = Account::SYSTEMS[0];

        (new StoreAccountAction)($user->id, [
            'balance' => 5000,
            'currency' => $currency,
            'type' => Account::CARD_TYPE,
            'details' => [
                'card_number' => '3534 3455 3453 3453',
                'card_holder' => 'CARD HOLDER',
                'expires_at' => '08/27',
                'system' => $system,
                'bank_id' => $bank->id,
            ],
        ]);

        $this->assertEquals(1, Account::query()->count());
        tap(Account::query()->first(), function (Account $account) use ($user, $currency, $bank, $system) {
            $this->assertEquals($user->id, $account->user_id);
            $this->assertEquals(500000, $account->balance);
            $this->assertEquals($currency, $account->currency);
            $this->assertEquals('3534 3455 3453 3453', $account->details->getNumber());
            $this->assertEquals('CARD HOLDER', $account->details->getHolder());
            $this->assertEquals(
                Carbon::createFromFormat('m/y', '08/27')->format('m/Y'),
                $account->details->getExpiresAt()->format('m/Y')
            );
            $this->assertEquals($system, $account->details->getSystem());
            $this->assertEquals($bank->id, $account->details->getBank()->id);
        });
    }
}
