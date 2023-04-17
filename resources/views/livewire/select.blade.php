<div class="relative">
    @if($title != null)
        <label for="{{ $inputName }}"
               class="block mb-2 text-sm font-medium text-gray-900">{{ $title }}</label>
    @endif
    <input type="hidden" name="{{ $inputName }}" value="{{ $selected }}">
    <div wire:click="setDropdownOpened({{ $dropdownOpened ? 'false' : 'true' }})"
         class="border cursor-pointer rounded-xl py-2 px-4 text-sm hover:bg-gray-100 transition">
        @if($selectedElem != null)
            <div class="font-medium">{{ $selectedElem['title'] }}</div>
            <div>{{ $selectedElem['subtitle'] }}</div>
        @else
            <div class="font-medium">&nbsp;</div>
            <div>{{ __('main.not_selected') }}</div>
        @endif
    </div>
    @if($dropdownOpened)
        <div class="z-10 absolute shadow-xl text-sm bg-white overflow-y-auto rounded-xl border w-full mt-1">
            <input type="text"
                   class="w-full text-sm border-transparent focus:border-transparent focus:ring-0"
                   placeholder="{{ __('main.search') }}...">
            <div class="border-t max-h-64">
                {!! $this->renderList($list) !!}
            </div>
        </div>
    @endif
    @error($inputName)
    <div class="text-sm px-2 text-red-500 mt-1">
        {{ $message }}
    </div>
    @enderror
</div>
