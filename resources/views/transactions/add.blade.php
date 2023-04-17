@extends('layouts.dashboard')
@section('content')
    <x-page-struct.form>
        <form method="POST" action="{{ route('transactions') }}">
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
                <livewire:select :list="$types"
                                 input-name="type"
                                 :title="__('validation.attributes.type')"
                                 :selected="array_key_first($types)"/>
                <x-inputs.input
                    type="number"
                    name="amount"
                    step=".01"
                    :value="old('amount', 0)"
                    :placeholder="__('validation.attributes.amount')"
                    :title="__('validation.attributes.amount')"
                    :is-required="true"
                />
                <x-inputs.input
                    type="datetime-local"
                    name="date"
                    :value="old('date', $defaultDate)"
                    :placeholder="__('validation.attributes.date')"
                    :title="__('validation.attributes.date')"
                    :is-required="true"
                />
            </div>
            <div class="mt-5">
                <x-buttons.blue :title="__('main.add')" classes="w-full"/>
            </div>
        </form>
    </x-page-struct.form>
@endsection
