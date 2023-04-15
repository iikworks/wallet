<div
    class="bg-white rounded-xl h-28 shadow-sm py-3 px-5">
    <div class="flex flex-col justify-between">
        <div class="flex justify-between items-center">
            <div class="text-gray-500 text-lg font-medium">
                {{ __('accounts.types.' . str_replace(' ', '_', $account->type)) }}
            </div>
            <div class="flex leading-5 justify-between items-center">
                <div class="text-xl text-gray-700 font-medium">
                    {{ currency_number($account->balance, $account->currency) }}
                </div>
            </div>
        </div>
        <div class="text-xs mt-5 text-gray-500">
            <div class="font-medium">
                <x-accounts.details :account="$account"/>
            </div>
        </div>
    </div>
</div>
