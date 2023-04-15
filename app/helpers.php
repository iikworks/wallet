<?php

use App\Models\Transaction;
use Illuminate\Support\Collection;

if (!function_exists('normalize_number')) {
    function normalize_number(int $number): int|float
    {
        return $number / 100;
    }
}

if (!function_exists('currency_number')) {
    function currency_number(int $number, string $currency): string
    {
        if (!array_key_exists($currency, config('constants.currencies')))
            throw new InvalidArgumentException("Currency $currency not found");

        $format = config('constants.currencies')[$currency]['format'];

        return sprintf($format, number_format(normalize_number($number), 2, '.', ' '));
    }
}

if (!function_exists('format_amount')) {
    function format_amount(int $amount, string $type, string $currency): string
    {
        if (!in_array($type, [Transaction::EXPENSE_TYPE, Transaction::REPLENISHMENT_TYPE]))
            throw new InvalidArgumentException("Currency $currency not found");

        if ($type == Transaction::EXPENSE_TYPE) $type = '-';
        if ($type == Transaction::REPLENISHMENT_TYPE) $type = '+';

        return $type . currency_number($amount, $currency);
    }
}

if (!function_exists('hide_card_number')) {
    function hide_card_number(string $number): string
    {
        return substr($number, 0, 6) . '***' . substr($number, -3, 3);
    }
}

if (!function_exists('hide_bank_account_number')) {
    function hide_bank_account_number(string $number): string
    {
        return substr($number, 0, 6) . '***' . substr($number, -4, 4);
    }
}

if (!function_exists('currencies_list')) {
    function currencies_list(): Collection
    {
        $currencies = collect(array_keys(config('constants.currencies')));
        $list = collect();

        foreach ($currencies as $currency) {
            $list[$currency] = __('main.currencies.' . $currency);
        }

        return $list;
    }
}
