@extends('layouts.dashboard')
@section('content')
    <div>
        <x-page-struct.form>
            <form method="POST"
                  action="{{ route('organizations.destroy', ['id' => $organization->id]) }}">
                @csrf
                @method('delete')
                <div class="font-medium text-2xl">
                    {{ $title }}
                </div>
                <div class="mt-5 space-y-3">
                    Вы действительно хотите удалить организацию <span
                        class="font-medium">{{ $organization->title }}</span>?
                </div>
                <div class="mt-5">
                    <x-buttons.blue :title="__('main.delete')" classes="w-full"/>
                </div>
            </form>
        </x-page-struct.form>
    </div>
@endsection
