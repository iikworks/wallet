@extends('layouts.dashboard')
@section('content')
    <x-page-struct.form>
        <form method="POST"
              action="{{ $action }}">
            @csrf
            <div class="font-medium text-2xl">
                {{ $title }}
            </div>
            <div class="mt-5 space-y-3">
                <x-inputs.input
                    type="text"
                    name="title"
                    :value="old('title', $bank ? $bank->title : '')"
                    :placeholder="__('validation.attributes.title')"
                    :title="__('validation.attributes.title')"
                    :is-required="true"
                />
            </div>
            <div class="mt-5">
                <x-buttons.blue :title="$bank ? __('main.save') : __('main.add')" classes="w-full"/>
            </div>
        </form>
    </x-page-struct.form>
@endsection
