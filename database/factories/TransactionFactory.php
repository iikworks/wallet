<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\Organization;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'account_id' => Account::all()->random()->id,
            'organization_id' => Organization::all()->random()->id,
            'type' => collect([Transaction::EXPENSE_TYPE, Transaction::REPLENISHMENT_TYPE])->random(),
            'amount' => rand(1, 100000),
            'date' => Carbon::make(fake()->dateTimeBetween('-1 month')),
        ];
    }
}
