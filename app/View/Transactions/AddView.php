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
        $types = collect([
            Transaction::EXPENSE_TYPE => __('transactions.types.expense'),
            Transaction::REPLENISHMENT_TYPE => __('transactions.types.replenishment'),
        ]);

        return view('transactions.add', [
            'title' => __('transactions.adding'),
            'accounts' => $accounts,
            'organizations' => $organizations,
            'types' => $types,
            'defaultDate' => now()->format('Y-m-d\TH:i'),
        ]);
    }
}
