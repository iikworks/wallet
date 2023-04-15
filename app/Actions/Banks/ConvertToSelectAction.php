<?php

namespace App\Actions\Banks;

use Illuminate\Support\Collection;

readonly class ConvertToSelectAction
{
    public function __invoke(Collection $banks): Collection
    {
        $banksForSelect = collect();

        foreach ($banks as $bank) {
            $banksForSelect[$bank->id] = $bank->title;
        }

        return $banksForSelect;
    }
}
