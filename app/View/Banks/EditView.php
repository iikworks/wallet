<?php

namespace App\View\Banks;

use App\Models\Bank;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

class EditView
{
    public function __invoke(int $bankId): View|Application|Factory
    {
        $bank = Bank::query()->findOrFail($bankId);

        $action = route('banks.update', ['id' => $bank->id]);

        return view('banks.add', [
            'title' => __('banks.editing'),
            'bank' => $bank,
            'action' => $action,
        ]);
    }
}
