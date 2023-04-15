<div class="relative bg-white rounded-xl overflow-x-auto">
    <table class="w-full text-sm text-left">
        <tbody>
        {{ $slot }}
        </tbody>
    </table>
    @if($link && $linkTitle)
        <div class="flex justify-center py-2">
            <a class="text-sm font-medium text-gray-500 hover:text-blue-400 transition"
               href="{{ $link }}">{{ $linkTitle }}</a>
        </div>
    @endif
</div>
