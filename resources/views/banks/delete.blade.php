@extends('layouts.dashboard')
@section('content')
    <div>
        <x-page-struct.form>
            <form method="POST"
                  action="{{ route('banks.destroy', ['id' => $bank->id]) }}">
                @csrf
                @method('delete')
                <div class="font-medium text-2xl">
                    {{ $title }}
                </div>
                <div class="mt-5 space-y-3">
                    {{ __('banks.delete_confirmation') }} <span
                        class="font-medium">{{ $bank->title }}</span>?
                </div>
                <div class="mt-5">
                    <x-buttons.blue :title="__('main.delete')" classes="w-full"/>
                </div>
            </form>
        </x-page-struct.form>
    </div>
@endsection
