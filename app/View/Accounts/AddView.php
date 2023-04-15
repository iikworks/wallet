<?php

namespace App\View\Accounts;

use App\Actions\Accounts\ConvertCardSystemsToSelectAction;
use App\Actions\Banks\ConvertToSelectAction;
use App\Models\Account;
use App\Models\Bank;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

class AddView
{
    public function __invoke(User $user, string|null $type): View|Application|Factory
    {
        $types = collect([Account::CASH_TYPE, Account::CARD_TYPE, Account::BANK_ACCOUNT_TYPE]);
        $currencies = currencies_list();
        $currency = config('app.currency');

        if ($type && !$types->contains($type)) abort(404);

        if (in_array($type, [Account::CARD_TYPE, Account::BANK_ACCOUNT_TYPE])) {
            $banks = (new ConvertToSelectAction)(Bank::all());
        } else $banks = null;

        if ($type == Account::CARD_TYPE) {
            $systems = (new ConvertCardSystemsToSelectAction)(collect(Account::SYSTEMS));
            $defaultSystem = $systems[Account::SYSTEMS[0]];
        } else {
            $systems = null;
            $defaultSystem = null;
        }

        return view('accounts.add', [
            'title' => __('accounts.adding'),
            'types' => $types,
            'type' => $type,
            'currencies' => $currencies,
            'banks' => $banks,
            'currency' => $currency,
            'systems' => $systems,
            'defaultSystem' => $defaultSystem,
        ]);
    }
}
