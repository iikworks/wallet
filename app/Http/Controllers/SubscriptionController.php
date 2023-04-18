<?php

namespace App\Http\Controllers;

use App\Actions\Subscriptions\StoreSubscriptionAction;
use App\Http\Requests\Subscriptions\StoreRequest;
use App\View\Subscriptions\AddView;
use App\View\Subscriptions\ListView;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function list(Request $request, ListView $view): View|Application|Factory
    {
        return ($view)($request->user(), intval($request->query('page', 1)));
    }

    public function add(Request $request, AddView $view): View|Application|Factory
    {
        return ($view)($request->user());
    }

    public function store(StoreRequest $request, StoreSubscriptionAction $action): RedirectResponse
    {
        ($action)($request->validated(), $request->user());
        return redirect()->route('subscriptions');
    }
}
