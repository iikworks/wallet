<?php

namespace App\Http\Controllers;

use App\Http\Resources\CardSystemCollection;
use App\Models\Account;
use App\Models\CardSystem;
use Illuminate\Http\JsonResponse;

class CardSystemController extends Controller
{
    public function list(): JsonResponse
    {
        $cardSystems = collect();
        foreach (Account::SYSTEMS as $systemTitle) {
            $cardSystem = new CardSystem();
            $cardSystem->title = $systemTitle;

            $cardSystems->add($cardSystem);
        }

        return response()->json([
            'data' => new CardSystemCollection($cardSystems),
        ]);
    }
}
