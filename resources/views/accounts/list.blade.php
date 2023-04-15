@extends('layouts.dashboard')
@section('content')
    <div>
        <x-page-struct.title :title="__('accounts.title')" :classes="'mb-3'">
            <x-buttons.plus :link="route('accounts.add')"/>
        </x-page-struct.title>
        <x-page-struct.columns :mdCols="1" :lgCols="3">
            @foreach($accounts as $account)
                <x-accounts.card :account="$account"/>
            @endforeach
        </x-page-struct.columns>
        @if($accounts->hasPages())
            <div class="mt-5">
                {{ $accounts->links() }}
            </div>
        @endif
        @if($accounts->count() == 0)
            <x-alerts.gray>
                {{ __('accounts.empty') }}
            </x-alerts.gray>
        @endif
    </div>
@endsection
