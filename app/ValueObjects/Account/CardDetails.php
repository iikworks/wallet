<?php

namespace App\ValueObjects\Account;

use App\Models\Account;
use App\Models\Bank;
use Carbon\Carbon;
use Webmozart\Assert\Assert;

final class CardDetails
{
    private string $number;
    private string $holder;
    private Carbon $expiresAt;
    private string $system;
    private Bank $bank;

    public function __construct(string $number, string $holder, Carbon $expiresAt, string $system, Bank $bank)
    {
        Assert::inArray($system, Account::SYSTEMS);

        $this->number = $number;
        $this->holder = $holder;
        $this->expiresAt = $expiresAt;
        $this->system = $system;
        $this->bank = $bank;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function getHolder(): string
    {
        return $this->holder;
    }

    public function getExpiresAt(): Carbon
    {
        return $this->expiresAt;
    }

    public function getSystem(): string
    {
        return $this->system;
    }

    public function getBank(): Bank
    {
        return $this->bank;
    }
}
