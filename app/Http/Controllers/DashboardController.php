<?php

namespace App\Http\Controllers;

use App\Actions\Dashboard\DashboardAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard(Request $request, DashboardAction $action): JsonResponse
    {
        return ($action)($request->user());
    }
}
