@extends('layouts.dashboard')
@section('content')
    <x-page-struct.form>
        <form method="POST" action="{{ route('subscriptions') }}">
            @csrf
            <div class="font-medium text-2xl">
                {{ $title }}
            </div>
            <div class="mt-5 space-y-3">
                <livewire:select :list="$accounts"
                                 input-name="account_id"
                                 :title="__('validation.attributes.account_id')"
                                 :selected="array_key_first($accounts)"/>
                <livewire:select :list="$organizations"
                                 input-name="organization_id"
                                 :title="__('validation.attributes.organization_id')"
                                 :selected="array_key_first($organizations)"/>
                <livewire:select :list="$currencies"
                                 input-name="currency"
                                 :title="__('validation.attributes.currency')"
                                 :selected="array_key_first($currencies)"/>
                <x-inputs.input
                    type="number"
                    name="amount"
                    step=".01"
                    :value="old('amount', 0.01)"
                    :placeholder="__('validation.attributes.amount')"
                    :title="__('validation.attributes.amount')"
                    :is-required="true"
                />
                <x-inputs.input
                    type="number"
                    name="day"
                    step="1"
                    min="1"
                    max="31"
                    :value="old('day', 1)"
                    :placeholder="__('validation.attributes.day')"
                    :title="__('validation.attributes.day')"
                    :is-required="true"
                />
            </div>
            <div class="mt-5">
                <x-buttons.blue :title="__('main.add')" classes="w-full"/>
            </div>
        </form>
    </x-page-struct.form>
@endsection
