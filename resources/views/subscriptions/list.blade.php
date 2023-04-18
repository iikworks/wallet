@extends('layouts.dashboard')
@section('content')
    <div>
        <x-page-struct.title :title="__('subscriptions.title')" :classes="'mb-3'">
            <x-buttons.plus :link="route('subscriptions.add')"/>
        </x-page-struct.title>
        <x-page-struct.columns :mdCols="1" :lgCols="3">
            @foreach($subscriptions as $subscription)
                <x-subscriptions.subscription :subscription="$subscription"/>
            @endforeach
        </x-page-struct.columns>
        @if($subscriptions->hasPages())
            <div class="mt-5">
                {{ $subscriptions->links() }}
            </div>
        @endif
        @if($subscriptions->count() == 0)
            <x-alerts.gray>
                {{ __('subscriptions.empty') }}
            </x-alerts.gray>
        @endif
    </div>
@endsection
