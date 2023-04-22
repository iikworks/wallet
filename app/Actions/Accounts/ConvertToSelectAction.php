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
            $title = match ($account->type) {
                Account::CASH_TYPE => __('accounts.types.' . str_replace(' ', '_', $account->type)),
                Account::BANK_ACCOUNT_TYPE => sprintf(
                    '%s %s',
                    __('accounts.types.' . str_replace(' ', '_', $account->type)),
                    hide_bank_account_number($account->details->getNumber()),
                ),
                Account::CARD_TYPE => sprintf(
                    '%s %s',
                    __('accounts.types.' . str_replace(' ', '_', $account->type)),
                    hide_card_number($account->details->getNumber()),
                ),
                default => '',
            };

            $accountsForSelect[$account->id] = [
                'title' => $title,
                'subtitle' => currency_number($account->balance, $account->currency),
            ];
        }

        return $accountsForSelect;
    }
}
