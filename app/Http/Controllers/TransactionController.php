<?php

namespace App\Http\Controllers;

use App\Actions\Transactions\GetUserTransactionByIdAction;
use App\Actions\Transactions\GetUserTransactionsAction;
use App\Actions\Transactions\StoreTransactionAndUpdateAccountBalanceAction;
use App\Http\Requests\Transactions\StoreRequest;
use App\Http\Resources\TransactionCollection;
use App\Http\Resources\TransactionResource;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TransactionController extends Controller
{
    public function getAll(Request $request, GetUserTransactionsAction $action): TransactionCollection
    {
        return new TransactionCollection(($action)(
            $request->user(),
            $request->query('page', 1),
            $request->query('limit', 15),
        ));
    }

    public function getOne(Request $request, GetUserTransactionByIdAction $action, int $subscriptionId): TransactionResource
    {
        $transaction = ($action)($request->user(), $subscriptionId);
        if (!$transaction) throw new NotFoundHttpException();

        return new TransactionResource($transaction);
    }

    public function store(StoreRequest $request, StoreTransactionAndUpdateAccountBalanceAction $action): TransactionResource
    {
        return new TransactionResource(($action)($request->validated(), $request->user()));
    }
}
