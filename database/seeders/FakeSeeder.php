<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class FakeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            BankSeeder::class,
            OrganizationSeeder::class,
            AccountSeeder::class,
            TransactionSeeder::class,
            SubscriptionSeeder::class,
            ExchangeRateSeeder::class,
        ]);
    }
}
