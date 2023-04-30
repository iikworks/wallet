<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class CurrencyController extends Controller
{
    public function list(): JsonResponse
    {
        $currencies = [];
        foreach (array_keys(config('constants.currencies')) as $currency) {
            $currencies[$currency] = __('main.currencies.' . $currency);
        }

        return response()->json($currencies);
    }
}
