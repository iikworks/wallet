<?php

namespace App\Http\Controllers;

use App\View\DashboardView;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard(Request $request, DashboardView $view): View|Application|Factory
    {
        return ($view)($request->user());
    }
}
