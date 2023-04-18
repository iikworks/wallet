<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Banks\DestroyBankAction;
use App\Actions\Banks\StoreBankAction;
use App\Actions\Banks\UpdateBankAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Banks\StoreRequest;
use App\Http\Requests\Banks\UpdateRequest;
use App\View\Banks\AddView;
use App\View\Banks\DeleteView;
use App\View\Banks\EditView;
use App\View\Banks\ListView;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BankController extends Controller
{
    public function list(Request $request, ListView $view): View|Application|Factory
    {
        return ($view)($request->user(), intval($request->query('page', 1)));
    }

    public function add(AddView $view): View|Application|Factory
    {
        return ($view)();
    }

    public function store(StoreRequest $request, StoreBankAction $action): RedirectResponse
    {
        ($action)($request->validated());

        return redirect()->route('banks');
    }

    public function edit(EditView $view, string $organizationId): View|Application|Factory
    {
        return ($view)(intval($organizationId));
    }

    public function update(UpdateRequest $request, UpdateBankAction $action, string $organizationId): RedirectResponse
    {
        ($action)(intval($organizationId), $request->validated());

        return back();
    }

    public function delete(string $organizationId, DeleteView $view): View|Application|Factory
    {
        return ($view)(intval($organizationId));
    }

    public function destroy(DestroyBankAction $action, string $organizationId): RedirectResponse
    {
        ($action)(intval($organizationId));

        return redirect()->route('banks');
    }
}
