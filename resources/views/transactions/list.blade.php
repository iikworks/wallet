@extends('layouts.dashboard')
@section('content')
    <div>
        <x-page-struct.title :title="__('transactions.title')" :classes="'mb-3'">
            <x-buttons.plus :link="route('transactions.add')"/>
        </x-page-struct.title>
        <x-page-struct.table>
            @foreach($transactions as $transaction)
                <x-transactions.transaction-table-row :transaction="$transaction"/>
            @endforeach
        </x-page-struct.table>
        @if($transactions->hasPages())
            <div class="mt-5">
                {{ $transactions->links() }}
            </div>
        @endif
        @if($transactions->count() == 0)
            <x-alerts.gray>
                {{ __('transactions.empty') }}
            </x-alerts.gray>
        @endif
    </div>
@endsection
