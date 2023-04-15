@php use App\Models\Account; @endphp
@php use Illuminate\Support\Str; @endphp
<div class="font-medium">
    @switch($account->type)
        @case(Account::CASH_TYPE)
            <div>
                {{ __('accounts.types.cash') }}
            </div>
            @break

        @case(Account::CARD_TYPE)
            <div>
                {{ __('accounts.types.card') }}
                <span class="text-violet-300">{{ hide_card_number($account->details->getNumber()) }}</span>
            </div>
            <div
                class="leading-4 opacity-60">{{ Str::limit($account->details->getBank()->title, 20, '...') }}</div>
            @break

        @case(Account::BANK_ACCOUNT_TYPE)
            <div>
                {{ __('accounts.types.bank_account_short') }}
                <span class="text-violet-300">{{ hide_bank_account_number($account->details->getNumber()) }}</span>
            </div>
            <div
                class="leading-4 opacity-60">{{ Str::limit($account->details->getBank()->title, 20, '...') }}</div>
            @break
    @endswitch
</div>
