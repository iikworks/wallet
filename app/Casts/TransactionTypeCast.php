<?php

namespace App\Casts;

use App\Models\Transaction;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class TransactionTypeCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param array<string, mixed> $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return $value;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param array<string, mixed> $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if (!in_array($value, [Transaction::EXPENSE_TYPE, Transaction::REPLENISHMENT_TYPE]))
            throw new InvalidArgumentException('Invalid transaction type');

        return $value;
    }
}
