@php use App\Models\Account; @endphp
@extends('layouts.dashboard')
@section('content')
    @if(!$type)
        <x-page-struct.form>
            <div class="font-medium text-2xl">
                {{ __('accounts.select_type') }}
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                @foreach($types as $typeSelect)
                    <a href="?type={{ $typeSelect }}"
                       class="text-sm font-medium bg-blue-500 hover:bg-blue-700 text-white py-2 text-center rounded-xl transition"
                    >{{ __('accounts.types.' . str_replace(' ', '_', $typeSelect)) }}</a>
                @endforeach
            </div>
        </x-page-struct.form>
    @else
        <x-page-struct.form>
            <form method="POST" action="{{ route('accounts') }}">
                @csrf
                <div class="font-medium text-2xl">
                    @switch($type)
                        @case(Account::CASH_TYPE)
                            {{ __('accounts.adding_cash_type') }}
                            @break
                        @case(Account::BANK_ACCOUNT_TYPE)
                            {{ __('accounts.adding_bank_type') }}
                            @break
                        @case(Account::CARD_TYPE)
                            {{ __('accounts.adding_card_type') }}
                            @break
                    @endswitch
                </div>
                <div class="mt-5 space-y-3">
                    <x-inputs.input
                        type="number"
                        name="balance"
                        step=".01"
                        :value="old('balance', 0)"
                        :placeholder="__('validation.attributes.balance')"
                        :title="__('validation.attributes.balance')"
                        :is-required="true"
                    />
                    <livewire:select :list="$currencies"
                                     input-name="currency"
                                     :title="__('validation.attributes.currency')"
                                     :selected="array_key_first($currencies)"/>
                    <input type="hidden" name="type" value="{{ $type }}">
                    <div class="pt-5 space-y-3">
                        <x-errors.account-details/>
                        @switch($type)
                            @case(Account::BANK_ACCOUNT_TYPE)
                                <x-inputs.input
                                    type="text"
                                    name="details[account_number]"
                                    :value="old('details.account_number', '')"
                                    :placeholder="__('validation.attributes.account_number')"
                                    :title="__('validation.attributes.account_number')"
                                    :is-required="true"
                                />
                                <x-inputs.select
                                    name="details[bank_id]"
                                    :collection="$banks"
                                    :selected="old('details.bank_id', 1)"
                                    :title="__('validation.attributes.bank_id')"
                                />
                                @break
                            @case(Account::CARD_TYPE)
                                <x-inputs.input
                                    type="text"
                                    name="details[card_number]"
                                    :value="old('details.card_number', '')"
                                    :placeholder="__('validation.attributes.card_number')"
                                    :title="__('validation.attributes.card_number')"
                                    :is-required="true"
                                />
                                <x-inputs.input
                                    type="text"
                                    name="details[card_holder]"
                                    :value="old('details.card_holder', '')"
                                    :placeholder="__('validation.attributes.card_holder')"
                                    :title="__('validation.attributes.card_holder')"
                                    :is-required="true"
                                />
                                <x-inputs.input
                                    type="text"
                                    name="details[expires_at]"
                                    :value="old('details.expires_at', '')"
                                    :placeholder="__('validation.attributes.expires_at')"
                                    :title="__('validation.attributes.expires_at')"
                                    :is-required="true"
                                />
                                <x-inputs.select
                                    name="details[system]"
                                    :collection="$systems"
                                    :selected="old('details.system', $defaultSystem)"
                                    :title="__('validation.attributes.system')"
                                />
                                <x-inputs.select
                                    name="details[bank_id]"
                                    :collection="$banks"
                                    :selected="old('details.bank_id', 1)"
                                    :title="__('validation.attributes.bank_id')"
                                />
                                @break
                        @endswitch
                    </div>
                </div>
                <div class="mt-5">
                    <x-buttons.blue :title="__('main.add')" classes="w-full"/>
                </div>
            </form>
        </x-page-struct.form>
    @endif

    @vite('resources/js/imask.js')
@endsection
