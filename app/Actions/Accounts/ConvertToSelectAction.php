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
            switch ($account->type) {
                case Account::CASH_TYPE:
                    $title = __('accounts.types.' . str_replace(' ', '_', $account->type));
                    break;
                case Account::BANK_ACCOUNT_TYPE:
                    $title = sprintf(
                        '%s %s',
                        __('accounts.types.' . str_replace(' ', '_', $account->type)),
                        hide_bank_account_number($account->details->getNumber()),
                    );
                    break;
                case Account::CARD_TYPE:
                    $title = sprintf(
                        '%s %s',
                        __('accounts.types.' . str_replace(' ', '_', $account->type)),
                        hide_card_number($account->details->getNumber()),
                    );
                    break;
                default:
                    $title = '';
                    break;
            }

            $accountsForSelect[$account->id] = [
                'title' => $title,
                'subtitle' => currency_number($account->balance, $account->currency),
            ];
        }

        return $accountsForSelect;
    }
}
