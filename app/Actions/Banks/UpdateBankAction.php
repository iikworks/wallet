<?php

namespace App\Actions\Banks;

use App\Models\Bank;
use Illuminate\Database\Eloquent\ModelNotFoundException;

readonly class UpdateBankAction
{
    public function __invoke(int $bankId, array $data): Bank
    {
        $bank = Bank::query()->find($bankId);
        if (!$bank)
            throw new ModelNotFoundException("bank id not found");

        $bank->fill($data);
        $bank->save();

        return $bank;
    }
}
