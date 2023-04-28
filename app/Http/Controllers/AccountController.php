<?php

namespace App\Http\Controllers;

use App\Actions\Accounts\StoreAccountAction;
use App\Http\Requests\Accounts\StoreRequest;
use App\Http\Resources\AccountCollection;
use App\Http\Resources\AccountResource;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function getAll(Request $request): AccountCollection
    {
        return new AccountCollection([
            'data' => $request->user()
                ->accounts()
                ->with('user')
                ->get()
        ]);
    }

    public function get(Request $request): AccountCollection
    {
        return new AccountCollection(
            $request->user()
                ->accounts()
                ->with('user')
                ->paginate(
                    perPage: $request->query('limit', 15),
                    page: $request->query('page', 1),
                )
        );
    }

    public function getOne(Request $request, int $accountId): AccountResource
    {
        return new AccountResource(
            $request->user()
                ->accounts()
                ->with('user')
                ->where('id', $accountId)
                ->firstOrFail()
        );
    }

    public function store(StoreRequest $request, StoreAccountAction $action): AccountResource
    {
        return new AccountResource(($action)($request->user()->id, $request->validated()));
    }
}
