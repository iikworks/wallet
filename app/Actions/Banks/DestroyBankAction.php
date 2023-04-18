<?php

namespace App\Actions\Banks;

use App\Models\Bank;

readonly class DestroyBankAction
{
    public function __invoke(int $bankId): void
    {
        Bank::query()->findOrFail($bankId)->delete();
    }
}
