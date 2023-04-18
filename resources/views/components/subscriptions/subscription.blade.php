<div class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 rounded-xl shadow-sm py-3 px-4">
    <div class="flex justify-between items-center">
        <div class="text-gray-200 text-lg font-medium">
            {{ Str::limit($subscription->organization->title, 15, '...') }}
        </div>
        <div class="text-gray-200 opacity-70 text-sm">
            @if($daysBeforePayment > 1)
                {{ trans_choice('main.after_days', $daysBeforePayment, ['days' => $daysBeforePayment]) }}
            @elseif($daysBeforePayment == 1)
                {{ __('main.tomorrow') }}
            @else
                {{ __('main.today') }}
            @endif
        </div>
    </div>
    <div class="mt-2 flex leading-5 justify-between items-center">
        <div class="text-xs text-gray-200">
            <x-accounts.details :account="$subscription->account"/>
        </div>
        <div class="text-lg text-gray-200 text-lg font-medium">
            {{ currency_number($subscription->amount, $subscription->currency) }}
        </div>
    </div>
</div>
