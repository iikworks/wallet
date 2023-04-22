<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Banks\DestroyBankAction;
use App\Actions\Banks\StoreBankAction;
use App\Actions\Banks\UpdateBankAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Banks\StoreRequest;
use App\Http\Requests\Banks\UpdateRequest;
use App\Http\Resources\BankCollection;
use App\Http\Resources\BankResource;
use App\Models\Bank;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BankController extends Controller
{
    public function getAll(Request $request): BankCollection
    {
        return new BankCollection(Bank::query()
            ->latest('created_at')
            ->paginate(
                perPage: $request->query('limit', 50),
                page: $request->query('page', 1),
            ));
    }

    public function getOne(int $bankId): BankResource
    {
        return new BankResource(Bank::query()->findOrFail($bankId));
    }

    public function store(StoreRequest $request, StoreBankAction $action): BankResource
    {
        return new BankResource(($action)($request->validated()));
    }

    public function update(UpdateRequest $request, UpdateBankAction $action, int $bankId): BankResource
    {
        return new BankResource(($action)($bankId, $request->validated()));
    }

    public function destroy(DestroyBankAction $action, int $bankId): JsonResponse
    {
        ($action)($bankId);

        return response()->json([
            'status' => 'ok',
        ]);
    }
}
