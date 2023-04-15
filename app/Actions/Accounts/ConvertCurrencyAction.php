<?php

namespace App\Actions\Accounts;

use App\Models\ExchangeRate;
use InvalidArgumentException;

readonly class ConvertCurrencyAction
{
    public function __invoke(string $from, string $to, int $amount): int
    {
        $rate = ExchangeRate::query()->where([
            'from' => $from,
            'to' => $to,
        ])->first();
        if (!$rate) throw new InvalidArgumentException("Exchange rate from $from to $to not found");

        $amount = round($amount / 100, 2);
        $rate = round($rate->rate / 10000, 2);

        return round($amount / $rate, 2) * 100;
    }
}
