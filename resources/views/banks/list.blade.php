@extends('layouts.dashboard')
@section('content')
    <div>
        <x-page-struct.title :title="__('banks.title')" :classes="'mb-3'">
            <x-buttons.plus :link="route('banks.add')"/>
        </x-page-struct.title>
        <x-page-struct.table>
            @foreach($banks as $bank)
                <tr class="flex flex-wrap lg:table-row border-b-2 md:border-b border-gray-200 md:border-gray-100">
                    <td class="px-5 md:px-4 py-3 md:py-3 w-full md:w-1/2 lg:w-1/4">
                        <div class="font-medium">
                            {{ $bank->id }}
                        </div>
                    </td>
                    <td class="px-5 md:px-5 py-3 md:py-3 w-full md:w-1/2 lg:w-1/4">
                        {{ $bank->title }}
                    </td>
                    <td class="px-5 md:px-5 py-3 md:py-3 w-full md:w-1/2 lg:w-1/4">
                        <div class="flex flex-col font-medium">
                            <a href="{{ route('banks.edit', ['id' => $bank->id]) }}"
                               class="hover:text-blue-400 transition">{{ __('main.edit') }}</a>
                            <a href="{{ route('banks.delete', ['id' => $bank->id]) }}"
                               class="hover:text-red-400 transition">{{ __('main.delete') }}</a>
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-page-struct.table>
        @if($banks->hasPages())
            <div class="mt-5">
                {{ $banks->links() }}
            </div>
        @endif
        @if($banks->count() == 0)
            <x-alerts.gray>
                {{ __('banks.empty') }}
            </x-alerts.gray>
        @endif
    </div>
@endsection
