<?php

namespace Database\Seeders;

use App\Models\ExchangeRate;
use Illuminate\Database\Seeder;

class ExchangeRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(!ExchangeRate::query()->where([
            'from' => 'BYN',
            'to' => 'USD',
        ])->first()) ExchangeRate::query()->create([
            'from' => 'BYN',
            'to' => 'USD',
            'rate' => 29264,
        ]);

        if(!ExchangeRate::query()->where([
            'from' => 'USD',
            'to' => 'BYN',
        ])->first()) ExchangeRate::query()->create([
            'from' => 'USD',
            'to' => 'BYN',
            'rate' => 3397,
        ]);
    }
}
