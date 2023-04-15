<?php

namespace App\Casts;

use App\Models\Account;
use App\Models\Bank;
use App\ValueObjects\Account\BankDetails;
use App\ValueObjects\Account\CardDetails;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class AccountDetailsCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param array<string, mixed> $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        $details = json_decode($value, true);

        if (in_array($attributes['type'], [Account::BANK_ACCOUNT_TYPE, Account::CARD_TYPE])) {
            $bank = Bank::query()->find($details['bank']);
            if (!$bank instanceof Bank) $bank = null;
        } else $bank = null;

        if ($attributes['type'] == Account::BANK_ACCOUNT_TYPE)
            return new BankDetails($details['number'], $bank);
        else if ($attributes['type'] == Account::CARD_TYPE)
            return new CardDetails(
                $details['number'],
                $details['holder'],
                Carbon::createFromFormat('m/y', $details['expires_at']),
                $details['system'],
                $bank,
            );

        return $value;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param array<string, mixed> $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): ?array
    {
        if ($value instanceof BankDetails) return [
            'type' => Account::BANK_ACCOUNT_TYPE,
            'details' => json_encode([
                'number' => $value->getNumber(),
                'bank' => $value->getBank()->id,
            ]),
        ];
        else if ($value instanceof CardDetails) return [
            'type' => Account::CARD_TYPE,
            'details' => json_encode([
                'number' => $value->getNumber(),
                'holder' => $value->getHolder(),
                'expires_at' => $value->getExpiresAt()->format('m/y'),
                'system' => $value->getSystem(),
                'bank' => $value->getBank()->id,
            ]),
        ];
        else if ($value == null) {
            return [
                'type' => Account::CASH_TYPE,
                'details' => null,
            ];
        }

        throw new InvalidArgumentException('wrong object type');
    }
}
