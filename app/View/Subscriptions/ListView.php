<?php

namespace App\View\Subscriptions;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

class ListView
{
    public function __invoke(User $user, int $page): View|Application|Factory
    {
        $accountsIds = $user->accounts->pluck('id');
        $subscriptions = Subscription::query()
            ->whereIn('account_id', $accountsIds)
            ->orderBy('day')
            ->paginate(page: $page);

        return view('subscriptions.list', [
            'title' => __('subscriptions.title'),
            'subscriptions' => $subscriptions,
        ]);
    }
}
