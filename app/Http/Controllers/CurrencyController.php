<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class CurrencyController extends Controller
{
    public function list(): JsonResponse
    {
        return response()->json(array_keys(config('constants.currencies')));
    }
}
