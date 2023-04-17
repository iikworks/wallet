<?php

namespace App\View\Transactions;

use App\Actions\Accounts\ConvertToSelectAction as ConvertAccountsToSelectAction;
use App\Actions\Organizations\ConvertToSelectAction as ConvertOrganizationsToSelectAction;
use App\Models\Organization;
use App\Models\Transaction;
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
        $types = [
            Transaction::EXPENSE_TYPE => [
                'title' => __('transactions.types.expense'),
                'subtitle' => '',
            ],
            Transaction::REPLENISHMENT_TYPE => [
                'title' => __('transactions.types.replenishment'),
                'subtitle' => '',
            ]
        ];

        return view('transactions.add', [
            'title' => __('transactions.adding'),
            'accounts' => $accounts->toArray(),
            'organizations' => $organizations->toArray(),
            'types' => $types,
            'defaultDate' => now()->format('Y-m-d\TH:i'),
        ]);
    }
}
