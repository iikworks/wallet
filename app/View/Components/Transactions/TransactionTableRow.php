<?php

namespace App\View\Components\Transactions;

use App\Models\Transaction;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TransactionTableRow extends Component
{
    private Transaction $transaction;

    /**
     * Create a new component instance.
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.transactions.transaction-table-row', [
            'transaction' => $this->transaction,
        ]);
    }
}
