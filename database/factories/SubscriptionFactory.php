<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\Organization;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends Factory<Subscription>
 */
class SubscriptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'account_id' => Account::query()
                ->where('type', Account::CARD_TYPE)
                ->get()
                ->random()
                ->id,
            'organization_id' => Organization::all()->random()->id,
            'amount' => rand(1, 500) * 10,
            'currency' => Arr::random(array_keys(config('constants.currencies'))),
            'day' => rand(1, 28),
        ];
    }
}
