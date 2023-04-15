<?php

namespace App\Http\Controllers;

use App\Actions\Transactions\StoreTransactionAndUpdateAccountBalanceAction;
use App\Http\Requests\Transactions\StoreRequest;
use App\View\Transactions\AddView;
use App\View\Transactions\ListView;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TransactionsController extends Controller
{
    public function list(Request $request, ListView $view): View|Application|Factory
    {
        return ($view)($request->user(), intval($request->query('page', 1)));
    }

    public function add(Request $request, AddView $view): View|Application|Factory
    {
        return ($view)($request->user());
    }

    public function store(StoreRequest $request, StoreTransactionAndUpdateAccountBalanceAction $action): RedirectResponse
    {
        ($action)($request->validated(), $request->user());
        return redirect()->route('transactions');
    }
}
