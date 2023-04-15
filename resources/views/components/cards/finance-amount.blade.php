@php use App\Models\Transaction; @endphp
<x-cards.card>
    <div class="text-gray-500">{{ $title }}</div>
    <div class="text-lg font-medium leading-5">
        <span class="@if($type == Transaction::EXPENSE_TYPE) text-red-500 @else text-green-500 @endif">
            @if($approximately)≈ @endif{{ format_amount($amount, $type, $currency) }}
        </span>
    </div>
</x-cards.card>
