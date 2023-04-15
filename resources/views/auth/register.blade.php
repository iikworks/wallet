@extends('layouts.auth')

@section('content')
    <div class="text-center mt-3 font-medium text-3xl">
        {{ __('main.wallet') }}
    </div>
    <div class="mb-8 leading-3 text-center font-medium text-gray-500">
        {{ __('auth.register') }}
    </div>
    <form method="POST" action="{{ route('register') }}">
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
                type="text"
                name="first_name"
                :placeholder="__('validation.attributes.first_name')"
                :is-required="true"
            />
            <x-inputs.input
                type="text"
                name="last_name"
                :placeholder="__('validation.attributes.last_name')"
                :is-required="true"
            />
            <x-inputs.input
                type="password"
                name="password"
                :placeholder="__('validation.attributes.password')"
                :is-required="true"
            />
            <x-inputs.input
                type="password"
                name="password_confirmation"
                :placeholder="__('validation.attributes.password_confirmation')"
                :is-required="true"
            />
        </div>
        <div class="border-t-2 mt-3 pt-3">
            <x-buttons.blue :title="__('auth.make_register')" :classes="'w-full text-sm'" />
        </div>
    </form>
    <div class="text-sm text-gray-500 mt-1 px-2">
        {{ __('auth.already_have_account') }}
        <a href="{{ route('login') }}" class="text-blue-500 hover:text-blue-700">
            {{ __('auth.make_login') }}
        </a>
    </div>

    @vite('resources/js/imask.js')
@endsection
