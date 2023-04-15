<?php

namespace App\View\Components\Subscriptions;

use App\Models\Subscription as SubscriptionModel;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Subscription extends Component
{
    private SubscriptionModel $subscription;
    private int $daysBeforePayment;

    /**
     * Create a new component instance.
     */
    public function __construct(SubscriptionModel $subscription)
    {
        $this->subscription = $subscription;
        $this->daysBeforePayment = $subscription->daysBeforePayment();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.subscriptions.subscription', [
            'subscription' => $this->subscription,
            'daysBeforePayment' => $this->daysBeforePayment,
        ]);
    }
}
