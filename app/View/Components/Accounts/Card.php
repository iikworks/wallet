<?php

namespace App\View\Components\Accounts;

use App\Models\Account;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Card extends Component
{
    private Account $account;

    /**
     * Create a new component instance.
     */
    public function __construct(Account $account)
    {
        $this->account = $account;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.accounts.card', [
            'account' => $this->account,
        ]);
    }
}
