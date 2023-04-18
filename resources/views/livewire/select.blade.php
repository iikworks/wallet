<div class="relative">
    @if($title != null)
        <label for="{{ $inputName }}"
               class="block mb-2 text-sm font-medium text-gray-900">{{ $title }}</label>
    @endif
    <input type="hidden" name="{{ $inputName }}" value="{{ $selected }}">
    <div wire:click="setDropdownOpened({{ $dropdownOpened ? 'false' : 'true' }})"
         class="relative border cursor-pointer rounded-xl py-2 px-4 text-sm hover:bg-gray-100 transition">
        @if($selectedElem != null)
            <div class="font-medium">{{ $selectedElem['title'] }}</div>
            <div class="text-gray-500">{{ $selectedElem['subtitle'] }}</div>
        @else
            <div>&nbsp;</div>
            <div class="text-gray-500 font-medium">{{ __('main.not_selected') }}</div>
        @endif
        @php
            $scale = '0.5';
            $top = '-0.7';
            if ($selectedElem && $selectedElem['subtitle'] == '') {
                $scale = '0.3';
                $top = '-1.3';
            }
        @endphp
        <div wire:loading class="absolute right-1.5" style="top: {{ $top }}rem;scale: {{ $scale }};">
            <x-icons.loading/>
        </div>
    </div>
    @if($dropdownOpened)
        <div class="z-20 absolute shadow-xl text-sm bg-white overflow-y-auto rounded-xl border w-full mt-1">
            <input type="text"
                   wire:model="searchInput"
                   class="w-full text-sm border-transparent focus:border-transparent focus:ring-0"
                   placeholder="{{ __('main.search') }}...">
            <div class="border-t max-h-64">
                {!! $this->renderList($filteredList) !!}
                @if(empty($filteredList))
                    <div
                        wire:click="setDropdownOpened(false)"
                        class="border-b cursor-pointer py-2 px-4 pl-6 text-sm hover:bg-gray-100 transition"
                    >
                        <div class="font-medium text-gray-500">{{ __('main.search_empty') }}</div>
                    </div>
                @endif
            </div>
        </div>
    @endif
    @error($inputName)
    <div class="text-sm px-2 text-red-500 mt-1">
        {{ $message }}
    </div>
    @enderror
</div>
