@extends('layouts.auth')

@section('content')
    <div class="text-center mt-3 font-medium text-3xl">
        {{ __('main.wallet') }}
    </div>
    <div class="mb-8 leading-3 text-center font-medium text-gray-500">
        {{ __('auth.login') }}
    </div>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="space-y-2">
            <x-inputs.input
                type="text"
                name="phone"
                :placeholder="__('validation.attributes.phone')"
                :value="old('phone', '+375')"
                :is-required="true"
            />
            <x-inputs.input
                type="password"
                name="password"
                :placeholder="__('validation.attributes.password')"
                :is-required="true"
            />
        </div>
        <div class="border-t-2 mt-3 pt-3">
            <x-buttons.blue :title="__('auth.make_login')" :classes="'w-full text-sm'" />
        </div>
    </form>
    <div class="text-sm text-gray-500 mt-1 px-2">
        {{ __('auth.dont_have_account') }}
        <a href="{{ route('register') }}" class="text-blue-500 hover:text-blue-700">
            {{ __('auth.make_register') }}
        </a>
    </div>

    @vite('resources/js/imask.js')
@endsection
