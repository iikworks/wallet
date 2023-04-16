<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Organizations\DestroyOrganizationAction;
use App\Actions\Organizations\StoreOrganizationAction;
use App\Actions\Organizations\UpdateOrganizationAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Organizations\StoreRequest;
use App\Http\Requests\Organizations\UpdateRequest;
use App\View\Organizations\AddView;
use App\View\Organizations\EditView;
use App\View\Organizations\ListView;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function list(Request $request, ListView $view): View|Application|Factory
    {
        return ($view)($request->user(), intval($request->query('page', 1)));
    }

    public function add(Request $request, AddView $view): View|Application|Factory
    {
        return ($view)($request->user());
    }

    public function store(StoreRequest $request, StoreOrganizationAction $action): RedirectResponse
    {
        try {
            ($action)($request->validated());

            return redirect()->route('organizations');
        } catch (ModelNotFoundException) {
            return back()->withErrors(['parent_id' => __('organizations.not_found')]);
        }
    }

    public function edit(Request $request, EditView $view, string $organizationId): View|Application|Factory
    {
        return ($view)($request->user(), intval($organizationId));
    }

    public function update(UpdateRequest $request, UpdateOrganizationAction $action, string $organizationId): RedirectResponse
    {
        try {
            ($action)(intval($organizationId), $request->validated());

            return back();
        } catch (ModelNotFoundException $e) {
            if ($e->getMessage() == 'parent id not found') {
                return back()->withErrors(['parent_id' => __('organizations.not_found')]);
            } else abort(404);
        }
    }

    public function destroy(DestroyOrganizationAction $action, string $organizationId): RedirectResponse
    {
        ($action)(intval($organizationId));

        return redirect()->route('organizations');
    }
}
