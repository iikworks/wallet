<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
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
