<div>
    @if($title)
        <label for="{{ $name }}"
               class="block mb-2 text-sm font-medium text-gray-900">{{ $title }}</label>
    @endif
    <select id="{{ $name }}"
            name="{{ $name }}"
            @if($collection->count() == 0) disabled @endif
            class="@if($collection->count() == 0) opacity-50 @endif bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full py-2 px-3">
        @if($collection->count() == 0)
            <option>{{ __('main.data_empty') }}</option>
        @endif
        @foreach($collection as $value => $title)
            <option value="{{ $value }}" @if($value == $selected) selected @endif>{!! $title !!}</option>
        @endforeach
    </select>
    @error($name)
    <div class="text-sm px-2 text-red-500 mt-1">
        {{ $message }}
    </div>
    @enderror
</div>
