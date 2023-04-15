<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\Bank;
use App\Models\User;
use App\ValueObjects\Account\BankDetails;
use App\ValueObjects\Account\CardDetails;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * @extends Factory<Account>
 */
class AccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = collect([Account::CASH_TYPE, Account::BANK_ACCOUNT_TYPE, Account::CARD_TYPE]);
        $type = $types->random();

        if(User::query()->count() > 0) $user_id = User::all()->random()->id;
        else $user_id = User::factory()->create()->id;

        if(Bank::query()->count() > 0) $bank = Bank::all()->random();
        else $bank = Bank::factory()->create();

        if ($type == Account::BANK_ACCOUNT_TYPE) {
            $details = new BankDetails(fake()->iban('BY'), $bank);
        } else if ($type == Account::CARD_TYPE) {
            $details = new CardDetails(
                fake()->creditCardNumber,
                Str::upper(Str::transliterate(fake()->firstName . ' ' . fake()->lastName)),
                Carbon::make(fake()->dateTimeBetween('now', '+10 years')),
                Arr::random(Account::SYSTEMS),
                $bank,
            );
        } else $details = null;

        return [
            'user_id' => $user_id,
            'balance' => rand(0, 100000),
            'currency' => Arr::random(array_keys(config('constants.currencies'))),
            'details' => $details,
        ];
    }
}
