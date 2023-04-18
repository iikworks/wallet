<?php

namespace App\View\Subscriptions;

use App\Actions\Accounts\ConvertToSelectAction as ConvertAccountsToSelectAction;
use App\Actions\Organizations\ConvertToSelectAction as ConvertOrganizationsToSelectAction;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

class AddView
{
    public function __invoke(User $user): View|Application|Factory
    {
        $accounts = (new ConvertAccountsToSelectAction)($user->accounts);
        $organizations = (new ConvertOrganizationsToSelectAction)(Organization::query()
            ->where('parent_id', null)
            ->with('childrenRecursive')
            ->get());
        $currencies = currencies_list();

        return view('subscriptions.add', [
            'title' => __('subscriptions.adding'),
            'accounts' => $accounts->toArray(),
            'currencies' => $currencies,
            'organizations' => $organizations->toArray(),
        ]);
    }
}
