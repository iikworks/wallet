<x-cards.card>
    <div class="text-gray-500">{{ $title }}</div>
    <div class="text-lg font-medium leading-5">
        @if($approximately)≈ @endif{{ currency_number($balance, $currency) }}
    </div>
</x-cards.card>
