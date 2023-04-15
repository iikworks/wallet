<?php

namespace Tests\Feature\Actions\Accounts;

use App\Actions\Accounts\CalculateNewBalanceAction;
use App\Models\Transaction;
use InvalidArgumentException;
use Tests\TestCase;

class CalculateNewBalanceActionTest extends TestCase
{
    public function test_must_be_greater_than_zero(): void
    {
        $this->expectException(InvalidArgumentException::class);
        (new CalculateNewBalanceAction)(50000, Transaction::EXPENSE_TYPE, 0);
    }

    public function test_must_have_valid_type(): void
    {
        $this->expectException(InvalidArgumentException::class);
        (new CalculateNewBalanceAction)(50000, 'wrong', 500);
    }

    public function test_insufficient_funds(): void
    {
        $this->expectException(InvalidArgumentException::class);
        (new CalculateNewBalanceAction)(0, Transaction::EXPENSE_TYPE, 500);
    }

    public function test_expense_no_err(): void
    {
        $newBalance = (new CalculateNewBalanceAction)(50000, Transaction::EXPENSE_TYPE, 500);
        $this->assertEquals(49500, $newBalance);
    }

    public function test_replenishment_no_err(): void
    {
        $newBalance = (new CalculateNewBalanceAction)(50000, Transaction::REPLENISHMENT_TYPE, 500);
        $this->assertEquals(50500, $newBalance);
    }
}
