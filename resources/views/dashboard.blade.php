@php use App\Models\Transaction; @endphp
@extends('layouts.dashboard')
@section('content')
    <x-page-struct.columns>
        <x-cards.finance-balance
            :title="__('accounts.overall_balance')"
            :balance="$balance"
            :currency="$currency"
            :approximately="$hasOtherCurrencies"
        />
        @if($latestTransaction)
            <x-cards.finance-amount
                :title="__('transactions.one_latest')"
                :amount="$latestTransaction->amount"
                :type="$latestTransaction->type"
                :currency="$latestTransaction->account->currency"
            />
        @endif
        <x-cards.finance-amount
            :title="__('transactions.expense_at_this_month')"
            :amount="$expensesAtThisMonth"
            :type="Transaction::EXPENSE_TYPE"
            :currency="'BYN'"
            :approximately="$hasOtherCurrencies"
        />
        <x-cards.finance-amount
            :title="__('transactions.replenishments_at_this_month')"
            :amount="$replenishmentsAtThisMonth"
            :type="Transaction::REPLENISHMENT_TYPE"
            :currency="'BYN'"
            :approximately="$hasOtherCurrencies"
        />
    </x-page-struct.columns>
    <div class="mt-8">
        <x-page-struct.title :title="__('accounts.title')" :classes="'mb-3'">
            <x-buttons.plus :link="route('accounts.add')"/>
        </x-page-struct.title>
        <x-page-struct.columns :mdCols="1" :lgCols="3">
            @foreach($accounts as $account)
                <x-accounts.card :account="$account"/>
            @endforeach
        </x-page-struct.columns>
        @if($accountsCount > 3)
            <x-page-struct.go-to-all
                :link="route('accounts')"
                :title="__('accounts.all')"
            />
        @endif
        @if($accounts->count() == 0)
            <x-alerts.gray>
                {{ __('accounts.empty') }}
            </x-alerts.gray>
        @endif
    </div>
    <div class="mt-8">
        <x-page-struct.title :title="__('subscriptions.title')" :classes="'mb-3'">
            <x-buttons.plus :link="'#'"/>
        </x-page-struct.title>
        <x-page-struct.columns :mdCols="1" :lgCols="3">
            @foreach($subscriptions as $subscription)
                <x-subscriptions.subscription :subscription="$subscription"/>
            @endforeach
        </x-page-struct.columns>
        @if($subscriptionsCount > 3)
            <x-page-struct.go-to-all
                :link="route('accounts')"
                :title="__('subscriptions.all')"
            />
        @endif
        @if($subscriptions->count() == 0)
            <x-alerts.gray>
                {{ __('subscriptions.empty') }}
            </x-alerts.gray>
        @endif
    </div>
    <div class="mt-8">
        <x-page-struct.title :title="__('transactions.latest')" :classes="'mb-3'">
            <x-buttons.plus :link="route('transactions.add')"/>
        </x-page-struct.title>
        <x-page-struct.table :link="$transactionsCount > 10 ? route('transactions') : null"
                             :link-title="__('transactions.all')">
            @foreach($latestTransactions as $transaction)
                <x-transactions.transaction-table-row :transaction="$transaction"/>
            @endforeach
        </x-page-struct.table>
        @if($latestTransactions->count() == 0)
            <x-alerts.gray>
                {{ __('transactions.empty') }}
            </x-alerts.gray>
        @endif
    </div>
@endsection
