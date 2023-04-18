<?php

namespace App\View\Banks;

use App\Models\Bank;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

class ListView
{
    public function __invoke(User $user, int $page): View|Application|Factory
    {
        $banks = Bank::query()
            ->latest('created_at')
            ->paginate(50, page: $page);

        return view('banks.list', [
            'title' => __('banks.title'),
            'banks' => $banks,
        ]);
    }
}
