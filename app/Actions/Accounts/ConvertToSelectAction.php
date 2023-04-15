<?php

namespace App\Actions\Accounts;

use App\Models\Account;
use Illuminate\Support\Collection;

readonly class ConvertToSelectAction
{
    public function __invoke(Collection $accounts): Collection
    {
        $accountsForSelect = collect();

        foreach ($accounts as $account) {
            $string = '';
            switch ($account->type) {
                case Account::CASH_TYPE:
                    $string = sprintf(
                        '%s ID %d в %s',
                        __('accounts.types.' . str_replace(' ', '_', $account->type)),
                        $account->id,
                        $account->currency,
                    );
                    break;
                case Account::BANK_ACCOUNT_TYPE:
                    $string = sprintf(
                        '%s %s в %s',
                        __('accounts.types.' . str_replace(' ', '_', $account->type)),
                        hide_bank_account_number($account->details->getNumber()),
                        $account->currency,
                    );
                    break;
                case Account::CARD_TYPE:
                    $string = sprintf(
                        '%s %s в %s',
                        __('accounts.types.' . str_replace(' ', '_', $account->type)),
                        hide_card_number($account->details->getNumber()),
                        $account->currency,
                    );
                    break;
            }

            $accountsForSelect[$account->id] = $string;
        }

        return $accountsForSelect;
    }
}
