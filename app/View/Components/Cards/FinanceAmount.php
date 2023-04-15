<?php

namespace App\View\Components\Cards;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FinanceAmount extends Component
{
    private string $title;
    private int $amount;
    private string $type;
    private string $currency;
    private bool $approximately;

    /**
     * Create a new component instance.
     */
    public function __construct(string $title, int $amount, string $type, string $currency, bool $approximately = false)
    {
        $this->title = $title;
        $this->amount = $amount;
        $this->type = $type;
        $this->currency = $currency;
        $this->approximately = $approximately;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.cards.finance-amount', [
            'title' => $this->title,
            'amount' => $this->amount,
            'type' => $this->type,
            'currency' => $this->currency,
            'approximately' => $this->approximately,
        ]);
    }
}
