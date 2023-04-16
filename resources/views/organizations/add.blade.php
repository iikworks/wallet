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
                <x-inputs.select-children
                    name="parent_id"
                    :collection="$organizations"
                    :selected="old('parent_id', $parentId)"
                    :title="__('validation.attributes.parent_id')"
                />
                <x-inputs.input
                    type="text"
                    name="title"
                    :value="old('title', $organization ? $organization->title : '')"
                    :placeholder="__('validation.attributes.title')"
                    :title="__('validation.attributes.title')"
                    :is-required="true"
                />
                <x-inputs.input
                    type="text"
                    name="vulgar_title"
                    :value="old('vulgar_title', $organization ? $organization?->vulgar_title : '')"
                    :placeholder="__('validation.attributes.vulgar_title')"
                    :title="__('validation.attributes.vulgar_title')"
                    :is-required="true"
                />
            </div>
            <div class="mt-5">
                <x-buttons.blue :title="$organization ? __('main.save') : __('main.add')" classes="w-full"/>
            </div>
        </form>
    </x-page-struct.form>
@endsection
