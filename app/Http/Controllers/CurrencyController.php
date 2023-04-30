<?php

namespace App\Http\Controllers;

use App\Http\Resources\CurrencyCollection;
use App\Models\Currency;
use Illuminate\Http\JsonResponse;

class CurrencyController extends Controller
{
    public function list(): JsonResponse
    {
        $currencies = collect();
        foreach (array_keys(config('constants.currencies')) as $currencyCode) {
            $currency = new Currency();
            $currency->code = $currencyCode;
            $currency->title = __('main.currencies.' . $currencyCode);

            $currencies->add($currency);
        }

        return response()->json([
            'data' => new CurrencyCollection($currencies),
        ]);
    }
}
