<?php

namespace App\ValueObjects\Account;

use App\Models\Bank;

final class BankDetails
{
    private string $number;
    private Bank $bank;

    public function __construct(string $number, Bank $bank)
    {
        $this->number = $number;
        $this->bank = $bank;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function getBank(): Bank
    {
        return $this->bank;
    }
}
