<?php

namespace App\View\Accounts;

use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

class ListView
{
    public function __invoke(User $user, int $page): View|Application|Factory
    {
        $accounts = $user->accounts()->paginate(page: $page);

        return view('accounts.list', [
            'title' => __('accounts.title'),
            'accounts' => $accounts,
        ]);
    }
}
