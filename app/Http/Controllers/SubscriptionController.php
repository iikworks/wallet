<?php

namespace App\Http\Controllers;

use App\Actions\Subscriptions\GetUserSubscriptionByIdAction;
use App\Actions\Subscriptions\GetUserSubscriptionsAction;
use App\Actions\Subscriptions\StoreSubscriptionAction;
use App\Http\Requests\Subscriptions\StoreRequest;
use App\Http\Resources\SubscriptionCollection;
use App\Http\Resources\SubscriptionResource;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SubscriptionController extends Controller
{
    public function getAll(Request $request, GetUserSubscriptionsAction $action): SubscriptionCollection
    {
        return new SubscriptionCollection(($action)(
            $request->user(),
            $request->query('page', 1),
            $request->query('limit', 15),
        ));
    }

    public function getOne(Request $request, GetUserSubscriptionByIdAction $action, int $subscriptionId): SubscriptionResource
    {
        $subscription = ($action)($request->user(), $subscriptionId);
        if (!$subscription) throw new NotFoundHttpException();

        return new SubscriptionResource($subscription);
    }

    public function store(StoreRequest $request, StoreSubscriptionAction $action): SubscriptionResource
    {
        return new SubscriptionResource(($action)($request->validated(), $request->user()));
    }
}
