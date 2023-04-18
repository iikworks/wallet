<div>
    @if($title)
        <label for="{{ $name }}"
               class="block mb-2 text-sm font-medium text-gray-900">{{ $title }}</label>
    @endif
    <input type="{{ $type }}"
           id="{{ $name }}"
           name="{{ $name }}"
           @if($type == 'number') step="{{ $step }}" @endif
           @if($type == 'number' && $min != '') min="{{ $min }}" @endif
           @if($type == 'number' && $max != '') max="{{ $max }}" @endif
           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full py-2 px-3 {{ $classes }}"
           placeholder="{{ $placeholder }}"
           value="{{ $value }}"
           @if($isRequired) required @endif>
    @error($name)
    <div class="text-sm px-2 text-red-500 mt-1">
        {{ $message }}
    </div>
    @enderror
</div>
