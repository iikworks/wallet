<?php

namespace App\Actions\Accounts;

use App\Models\Transaction;
use InvalidArgumentException;

readonly class CalculateNewBalanceAction
{
    public function __invoke(int $balance, string $type, int $amount): int
    {
        if ($amount <= 0)
            throw new InvalidArgumentException('Amount must be greater than zero');

        if ($type == Transaction::EXPENSE_TYPE) {
            if ($amount > $balance)
                throw new InvalidArgumentException('Insufficient funds in the balance');

            return $balance - $amount;
        } else if ($type == Transaction::REPLENISHMENT_TYPE) return $balance + $amount;

        throw new InvalidArgumentException('Invalid type');
    }
}
