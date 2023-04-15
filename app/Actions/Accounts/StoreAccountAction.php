<?php

namespace App\Actions\Accounts;

use App\Models\Account;
use App\Models\Bank;
use App\ValueObjects\Account\BankDetails;
use App\ValueObjects\Account\CardDetails;
use Carbon\Carbon;

readonly class StoreAccountAction
{
    public function __invoke(int $userId, array $data): Account
    {
        $account = new Account();
        $account->user_id = $userId;
        $account->balance = $data['balance'] * 100;
        $account->currency = $data['currency'];

        if ($data['type'] == Account::BANK_ACCOUNT_TYPE) {
            $details = new BankDetails(
                $data['details']['account_number'],
                Bank::query()->findOrFail($data['details']['bank_id']),
            );
        } else if ($data['type'] == Account::CARD_TYPE) {
            $details = new CardDetails(
                $data['details']['card_number'],
                $data['details']['card_holder'],
                Carbon::createFromFormat('m/Y', $data['details']['expires_at']),
                $data['details']['system'],
                Bank::query()->findOrFail($data['details']['bank_id']),
            );
        } else $details = null;
        $account->details = $details;
        $account->save();

        return $account;
    }
}
