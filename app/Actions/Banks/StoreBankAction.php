<?php

namespace App\Actions\Banks;

use App\Models\Bank;

readonly class StoreBankAction
{
    public function __invoke(array $data): Bank
    {
        $bank = new Bank();
        $bank->fill($data);
        $bank->save();

        return $bank;
    }
}
