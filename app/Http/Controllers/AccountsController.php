<?php

namespace App\Http\Controllers;

use App\Actions\Accounts\StoreAccountAction;
use App\Http\Requests\Accounts\StoreRequest;
use App\View\Accounts\AddView;
use App\View\Accounts\ListView;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AccountsController extends Controller
{
    public function list(Request $request, ListView $view): View|Application|Factory
    {
        return ($view)($request->user(), intval($request->query('page', 1)));
    }

    public function add(Request $request, AddView $view): View|Application|Factory
    {
        return ($view)($request->user(), $request->query('type'));
    }

    public function store(StoreRequest $request, StoreAccountAction $action): RedirectResponse
    {
        ($action)($request->user()->id, $request->validated());
        return redirect()->route('accounts');
    }
}
