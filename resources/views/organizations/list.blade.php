@extends('layouts.dashboard')
@section('content')
    <div>
        <x-page-struct.title :title="__('organizations.title')" :classes="'mb-3'">
            <x-buttons.plus :link="route('organizations.add')"/>
        </x-page-struct.title>
        <x-page-struct.table>
            @foreach($organizations as $organization)
                <tr class="flex flex-wrap lg:table-row border-b-2 md:border-b border-gray-200 md:border-gray-100">
                    <td class="px-5 md:px-4 py-3 md:py-3 w-full md:w-1/2 lg:w-1/4">
                        <div class="font-medium">
                            {{ $organization->id }}
                        </div>
                    </td>
                    <td class="px-5 md:px-5 py-3 md:py-3 w-full md:w-1/2 lg:w-1/4">
                        {{ __('organizations.parent') }}:
                        @if($organization->parent_id)
                            <span class="font-medium">{{ $organization->parent->title }}</span>
                        @else
                            {{ __('main.no') }}
                        @endif
                    </td>
                    <td class="px-5 md:px-5 py-3 md:py-3 w-full md:w-1/2 lg:w-1/4">
                        <div>
                            {{ $organization->title }}
                        </div>
                        <div class="leading-4 text-gray-500">
                            {{ $organization->vulgar_title }}
                        </div>
                    </td>
                    <td class="px-5 md:px-5 py-3 md:py-3 w-full md:w-1/2 lg:w-1/4">
                        <div class="flex flex-col font-medium">
                            <a href="{{ route('organizations.edit', ['id' => $organization->id]) }}"
                               class="hover:text-blue-400 transition">{{ __('main.edit') }}</a>
                            <a href="{{ route('organizations.delete', ['id' => $organization->id]) }}"
                               class="hover:text-red-400 transition">{{ __('main.delete') }}</a>
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-page-struct.table>
        @if($organizations->hasPages())
            <div class="mt-5">
                {{ $organizations->links() }}
            </div>
        @endif
        @if($organizations->count() == 0)
            <x-alerts.gray>
                {{ __('organizations.empty') }}
            </x-alerts.gray>
        @endif
    </div>
@endsection
