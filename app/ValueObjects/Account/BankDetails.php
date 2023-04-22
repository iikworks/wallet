<?php

namespace App\ValueObjects\Account;

use App\Http\Resources\BankResource;
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

    public function toArray(): array
    {
        return [
            'number' => hide_bank_account_number($this->getNumber()),
            'bank' => (new BankResource($this->getBank()))->resolve(),
        ];
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
