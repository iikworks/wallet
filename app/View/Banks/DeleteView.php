<?php

namespace App\View\Banks;

use App\Models\Bank;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

class DeleteView
{
    public function __invoke(int $bankId): View|Application|Factory
    {
        $bank = Bank::query()->findOrFail($bankId);

        return view('banks.delete', [
            'title' => __('banks.deleting'),
            'bank' => $bank,
        ]);
    }
}
