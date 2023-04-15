<?php

namespace Tests\Feature\Casts;

use App\Models\Account;
use App\Models\Bank;
use App\Models\User;
use App\ValueObjects\Account\BankDetails;
use App\ValueObjects\Account\CardDetails;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Tests\TestCase;

class AccountDetailsCastTest extends TestCase
{
    use RefreshDatabase;

    public function test_cannot_set_with_wrong_object_type(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $account = new Account();
        $account->details = new Account();
    }

    public function test_can_set_cash_details(): void
    {
        $account = new Account();
        $account->user_id = User::factory()->create()->id;
        $account->balance = 500000;
        $account->currency = array_key_first(config('constants.currencies'));
        $account->details = null;
        $account->save();

        $this->assertEquals(Account::CASH_TYPE, $account->type);
    }

    public function test_can_set_bank_details(): void
    {
        $bank = Bank::factory()->create();

        $account = new Account();
        $account->user_id = User::factory()->create()->id;
        $account->balance = 500000;
        $account->currency = array_key_first(config('constants.currencies'));
        $account->details = new BankDetails('number', $bank);
        $account->save();

        $this->assertEquals(Account::BANK_ACCOUNT_TYPE, $account->type);
        $this->assertEquals('number', $account->details->getNumber());
        $this->assertEquals($bank->title, $account->details->getBank()->title);
    }

    public function test_can_set_card_details(): void
    {
        $bank = Bank::factory()->create();
        $expiresAt = Carbon::now()->addYears(5);

        $account = new Account();
        $account->user_id = User::factory()->create()->id;
        $account->balance = 500000;
        $account->currency = array_key_first(config('constants.currencies'));
        $account->details = new CardDetails('number', 'holder', $expiresAt, Account::SYSTEMS[0], $bank);
        $account->save();

        $this->assertEquals(Account::CARD_TYPE, $account->type);
        $this->assertEquals('number', $account->details->getNumber());
        $this->assertEquals('holder', $account->details->getHolder());
        $this->assertEquals($expiresAt, $account->details->getExpiresAt());
        $this->assertEquals(Account::SYSTEMS[0], $account->details->getSystem());
        $this->assertEquals($bank->title, $account->details->getBank()->title);
    }
}
