<?php

namespace Tests\Feature\Controllers\Accounts;

use App\Models\Account;
use App\Models\Bank;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreAccountTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_cant_store_a_new_account(): void
    {
        $response = $this->post(route('accounts'));
        $response->assertRedirectToRoute('login');
        $this->assertEquals(0, Account::query()->count());
    }

    public function test_can_store_a_new_cash_account(): void
    {
        $user = User::factory()->create();
        $currency = array_keys(config('constants.currencies'))[0];

        $response = $this->actingAs($user)->post(route('accounts'), [
            'balance' => 5000,
            'currency' => $currency,
            'type' => Account::CASH_TYPE,
        ]);
        $response->assertRedirectToRoute('accounts');


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

        $response = $this->actingAs($user)->post(route('accounts'), [
            'balance' => 5000,
            'currency' => $currency,
            'type' => Account::BANK_ACCOUNT_TYPE,
            'details' => [
                'account_number' => 'NUMBER',
                'bank_id' => $bank->id,
            ],
        ]);
        $response->assertRedirectToRoute('accounts');

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

        $response = $this->actingAs($user)->post(route('accounts'), [
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
        $response->assertRedirectToRoute('accounts');

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

    public function test_balance_field_required_to_create_a_new_account(): void
    {
        $user = User::factory()->create();
        $currency = array_keys(config('constants.currencies'))[0];

        $response = $this->actingAs($user)->post(route('accounts'), [
//            'balance' => 5000,
            'currency' => $currency,
            'type' => Account::CASH_TYPE,
        ]);

        $response->assertSessionHasErrorsIn('balance');
        $this->assertEquals(0, Account::query()->count());
    }

    public function test_balance_field_must_be_a_number(): void
    {
        $user = User::factory()->create();
        $currency = array_keys(config('constants.currencies'))[0];

        $response = $this->actingAs($user)->post(route('accounts'), [
            'balance' => 'string',
            'currency' => $currency,
            'type' => Account::CASH_TYPE,
        ]);

        $response->assertSessionHasErrorsIn('balance');
        $this->assertEquals(0, Account::query()->count());
    }

    public function test_balance_field_must_be_a_bigger_than_zero(): void
    {
        $user = User::factory()->create();
        $currency = array_keys(config('constants.currencies'))[0];

        $response = $this->actingAs($user)->post(route('accounts'), [
            'balance' => -10,
            'currency' => $currency,
            'type' => Account::CASH_TYPE,
        ]);

        $response->assertSessionHasErrorsIn('balance');
        $this->assertEquals(0, Account::query()->count());
    }

    public function test_currency_field_required_to_create_a_new_account(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('accounts'), [
            'balance' => 5000,
//            'currency' => $currency,
            'type' => Account::CASH_TYPE,
        ]);

        $response->assertSessionHasErrorsIn('balance');
        $this->assertEquals(0, Account::query()->count());
    }

    public function test_currency_field_must_be_available(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('accounts'), [
            'balance' => 5000,
            'currency' => 'wrong',
            'type' => Account::CASH_TYPE,
        ]);

        $response->assertSessionHasErrorsIn('currency');
        $this->assertEquals(0, Account::query()->count());
    }

    public function test_type_field_required_to_create_a_new_account(): void
    {
        $user = User::factory()->create();
        $currency = array_keys(config('constants.currencies'))[0];

        $response = $this->actingAs($user)->post(route('accounts'), [
            'balance' => 5000,
            'currency' => $currency,
//            'type' => Account::CASH_TYPE,
        ]);

        $response->assertSessionHasErrorsIn('balance');
        $this->assertEquals(0, Account::query()->count());
    }

    public function test_type_field_must_be_available(): void
    {
        $user = User::factory()->create();
        $currency = array_keys(config('constants.currencies'))[0];

        $response = $this->actingAs($user)->post(route('accounts'), [
            'balance' => 5000,
            'currency' => $currency,
            'type' => 'wrong',
        ]);

        $response->assertSessionHasErrorsIn('currency');
        $this->assertEquals(0, Account::query()->count());
    }

    public function test_details_number_field_required_to_create_a_new_bank_account(): void
    {
        $user = User::factory()->create();
        $bank = Bank::factory()->create();
        $currency = array_keys(config('constants.currencies'))[0];

        $response = $this->actingAs($user)->post(route('accounts'), [
            'balance' => 5000,
            'currency' => $currency,
            'type' => Account::BANK_ACCOUNT_TYPE,
            'details' => [
//                'account_number' => 'NUMBER',
                'bank_id' => $bank->id,
            ],
        ]);

        $response->assertSessionHasErrorsIn('details');
        $this->assertEquals(0, Account::query()->count());
    }

    public function test_details_bank_id_field_required_to_create_a_new_bank_account(): void
    {
        $user = User::factory()->create();
        $currency = array_keys(config('constants.currencies'))[0];

        $response = $this->actingAs($user)->post(route('accounts'), [
            'balance' => 5000,
            'currency' => $currency,
            'type' => Account::BANK_ACCOUNT_TYPE,
            'details' => [
                'account_number' => 'NUMBER',
//                'bank_id' => $bank->id,
            ],
        ]);

        $response->assertSessionHasErrorsIn('details');
        $this->assertEquals(0, Account::query()->count());
    }

    public function test_details_bank_id_must_be_exist_to_create_a_new_bank_account(): void
    {
        $user = User::factory()->create();
        $currency = array_keys(config('constants.currencies'))[0];

        $response = $this->actingAs($user)->post(route('accounts'), [
            'balance' => 5000,
            'currency' => $currency,
            'type' => Account::BANK_ACCOUNT_TYPE,
            'details' => [
                'account_number' => 'NUMBER',
                'bank_id' => 1,
            ],
        ]);

        $response->assertSessionHasErrorsIn('details');
        $this->assertEquals(0, Account::query()->count());
    }

    public function test_details_number_field_required_to_create_a_new_card_account(): void
    {
        $user = User::factory()->create();
        $bank = Bank::factory()->create();
        $currency = array_keys(config('constants.currencies'))[0];

        $response = $this->actingAs($user)->post(route('accounts'), [
            'balance' => 5000,
            'currency' => $currency,
            'type' => Account::CARD_TYPE,
            'details' => [
//                'card_number' => 'NUMBER',
                'card_holder' => 'CARD HOLDER',
                'expires_at' => '04/23',
                'system' => Account::SYSTEMS[0],
                'bank_id' => $bank->id,
            ],
        ]);

        $response->assertSessionHasErrorsIn('details');
        $this->assertEquals(0, Account::query()->count());
    }

    public function test_details_number_field_must_be_valid_to_create_a_new_card_account(): void
    {
        $user = User::factory()->create();
        $bank = Bank::factory()->create();
        $currency = array_keys(config('constants.currencies'))[0];

        $response = $this->actingAs($user)->post(route('accounts'), [
            'balance' => 5000,
            'currency' => $currency,
            'type' => Account::CARD_TYPE,
            'details' => [
                'card_number' => 'not valid',
                'card_holder' => 'CARD HOLDER',
                'expires_at' => '04/23',
                'system' => Account::SYSTEMS[0],
                'bank_id' => $bank->id,
            ],
        ]);

        $response->assertSessionHasErrorsIn('details');
        $this->assertEquals(0, Account::query()->count());
    }

    public function test_details_holder_field_required_to_create_a_new_card_account(): void
    {
        $user = User::factory()->create();
        $bank = Bank::factory()->create();
        $currency = array_keys(config('constants.currencies'))[0];

        $response = $this->actingAs($user)->post(route('accounts'), [
            'balance' => 5000,
            'currency' => $currency,
            'type' => Account::CARD_TYPE,
            'details' => [
                'card_number' => '4553 3453 3455 3535',
//                'card_holder' => 'CARD HOLDER',
                'expires_at' => '04/23',
                'system' => Account::SYSTEMS[0],
                'bank_id' => $bank->id,
            ],
        ]);

        $response->assertSessionHasErrorsIn('details');
        $this->assertEquals(0, Account::query()->count());
    }

    public function test_details_holder_must_be_uppercase_to_create_a_new_card_account(): void
    {
        $user = User::factory()->create();
        $bank = Bank::factory()->create();
        $currency = array_keys(config('constants.currencies'))[0];

        $response = $this->actingAs($user)->post(route('accounts'), [
            'balance' => 5000,
            'currency' => $currency,
            'type' => Account::CARD_TYPE,
            'details' => [
                'card_number' => '4553 3453 3455 3535',
                'card_holder' => 'lower case',
                'expires_at' => '04/23',
                'system' => Account::SYSTEMS[0],
                'bank_id' => $bank->id,
            ],
        ]);

        $response->assertSessionHasErrorsIn('details');
        $this->assertEquals(0, Account::query()->count());
    }

    public function test_details_expires_at_field_required_to_create_a_new_card_account(): void
    {
        $user = User::factory()->create();
        $bank = Bank::factory()->create();
        $currency = array_keys(config('constants.currencies'))[0];

        $response = $this->actingAs($user)->post(route('accounts'), [
            'balance' => 5000,
            'currency' => $currency,
            'type' => Account::CARD_TYPE,
            'details' => [
                'card_number' => '4553 3453 3455 3535',
                'card_holder' => 'CARD HOLDER',
//                'expires_at' => '04/23',
                'system' => Account::SYSTEMS[0],
                'bank_id' => $bank->id,
            ],
        ]);

        $response->assertSessionHasErrorsIn('details');
        $this->assertEquals(0, Account::query()->count());
    }

    public function test_details_expires_at_field_must_be_valid_to_create_a_new_card_account(): void
    {
        $user = User::factory()->create();
        $bank = Bank::factory()->create();
        $currency = array_keys(config('constants.currencies'))[0];

        $response = $this->actingAs($user)->post(route('accounts'), [
            'balance' => 5000,
            'currency' => $currency,
            'type' => Account::CARD_TYPE,
            'details' => [
                'card_number' => '4553 3453 3455 3535',
                'card_holder' => 'CARD HOLDER',
                'expires_at' => 'bad format',
                'system' => Account::SYSTEMS[0],
                'bank_id' => $bank->id,
            ],
        ]);

        $response->assertSessionHasErrorsIn('details');
        $this->assertEquals(0, Account::query()->count());
    }

    public function test_details_system_field_required_to_create_a_new_card_account(): void
    {
        $user = User::factory()->create();
        $bank = Bank::factory()->create();
        $currency = array_keys(config('constants.currencies'))[0];

        $response = $this->actingAs($user)->post(route('accounts'), [
            'balance' => 5000,
            'currency' => $currency,
            'type' => Account::CARD_TYPE,
            'details' => [
                'card_number' => '4553 3453 3455 3535',
                'card_holder' => 'CARD HOLDER',
                'expires_at' => '04/23',
//                'system' => Account::SYSTEMS[0],
                'bank_id' => $bank->id,
            ],
        ]);

        $response->assertSessionHasErrorsIn('details');
        $this->assertEquals(0, Account::query()->count());
    }

    public function test_details_system_field_must_be_valid_to_create_a_new_card_account(): void
    {
        $user = User::factory()->create();
        $bank = Bank::factory()->create();
        $currency = array_keys(config('constants.currencies'))[0];

        $response = $this->actingAs($user)->post(route('accounts'), [
            'balance' => 5000,
            'currency' => $currency,
            'type' => Account::CARD_TYPE,
            'details' => [
                'card_number' => '4553 3453 3455 3535',
                'card_holder' => 'CARD HOLDER',
                'expires_at' => '04/23',
                'system' => 'bad',
                'bank_id' => $bank->id,
            ],
        ]);

        $response->assertSessionHasErrorsIn('details');
        $this->assertEquals(0, Account::query()->count());
    }

    public function test_details_bank_id_field_required_to_create_a_new_card_account(): void
    {
        $user = User::factory()->create();
        $currency = array_keys(config('constants.currencies'))[0];

        $response = $this->actingAs($user)->post(route('accounts'), [
            'balance' => 5000,
            'currency' => $currency,
            'type' => Account::CARD_TYPE,
            'details' => [
                'card_number' => 'NUMBER',
                'card_holder' => 'CARD HOLDER',
                'expires_at' => '04/23',
                'system' => Account::SYSTEMS[0],
//                'bank_id' => $bank->id,
            ],
        ]);

        $response->assertSessionHasErrorsIn('details');
        $this->assertEquals(0, Account::query()->count());
    }

    public function test_details_bank_id_must_be_exist_to_create_a_new_card_account(): void
    {
        $user = User::factory()->create();
        $currency = array_keys(config('constants.currencies'))[0];

        $response = $this->actingAs($user)->post(route('accounts'), [
            'balance' => 5000,
            'currency' => $currency,
            'type' => Account::CARD_TYPE,
            'details' => [
//                'card_number' => 'NUMBER',
                'card_holder' => 'CARD HOLDER',
                'expires_at' => '04/23',
                'system' => Account::SYSTEMS[0],
                'bank_id' => 1,
            ],
        ]);

        $response->assertSessionHasErrorsIn('details');
        $this->assertEquals(0, Account::query()->count());
    }
}
