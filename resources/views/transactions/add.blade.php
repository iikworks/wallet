@extends('layouts.dashboard')
@section('content')
    <x-page-struct.form>
        <form method="POST" action="{{ route('transactions') }}">
            @csrf
            <div class="font-medium text-2xl">
                {{ $title }}
            </div>
            <div class="mt-5 space-y-3">
                <x-inputs.select
                    name="account_id"
                    :collection="$accounts"
                    :selected="old('account_id', $accounts->take(1)->keys()->first())"
                    :title="__('validation.attributes.account_id')"
                />
                <x-inputs.select-children
                    name="organization_id"
                    :collection="$organizations"
                    :selected="old('organization_id', $organizations->take(1)->keys()->first())"
                    :title="__('validation.attributes.organization_id')"
                />
                <x-inputs.select
                    name="type"
                    :collection="$types"
                    :selected="old('type', $types->first())"
                    :title="__('validation.attributes.type')"
                />
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
