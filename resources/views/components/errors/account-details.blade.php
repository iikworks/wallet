@error('details')
<div class="pb-2 space-y-2">
    @php
        $messages = json_decode($message, true);
    @endphp

    @foreach($messages as $errors)
        @foreach($errors as $error)
            <div class="text-sm text-red-500 font-medium">{{ $error }}</div>
        @endforeach
    @endforeach
</div>
@enderror
