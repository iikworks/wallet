<?php

namespace App\View\Components\Cards;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FinanceBalance extends Component
{
    private string $title;
    private int $balance;
    private string $currency;
    private bool $approximately;

    /**
     * Create a new component instance.
     */
    public function __construct(string $title, int $balance, string $currency, bool $approximately = false)
    {
        $this->title = $title;
        $this->balance = $balance;
        $this->currency = $currency;
        $this->approximately = $approximately;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.cards.finance-balance', [
            'title' => $this->title,
            'balance' => $this->balance,
            'currency' => $this->currency,
            'approximately' => $this->approximately,
        ]);
    }
}
