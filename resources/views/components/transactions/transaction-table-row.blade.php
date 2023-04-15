@php use App\Models\Transaction; @endphp
<tr class="font-medium flex flex-wrap lg:table-row border-b-2 md:border-b border-gray-200 md:border-gray-100">
    <td class="px-5 md:px-4 py-3 md:py-3 w-full md:w-1/2 lg:w-1/4">
        <div>
            {{ $transaction->date->format('d.m.Y') }}
        </div>
        <div class="leading-4 text-gray-500">
            {{ $transaction->date->format('H:i') }}
        </div>
    </td>
    <td class="px-5 md:px-5 py-3 md:py-3 w-full md:w-1/2 lg:w-1/4">
        <div>{{ \Illuminate\Support\Str::limit($transaction->organization->title, 20, '...') }}</div>
        <div class="leading-4 text-gray-500">
            {{ \Illuminate\Support\Str::limit($transaction->organization->vulgar_title, 20, '...') }}
        </div>
    </td>
    <td class="px-5 md:px-6 py-3 md:py-3 w-full md:w-1/2 lg:w-1/4">
        <x-accounts.details :account="$transaction->account"/>
    </td>
    <td class="px-5 md:px-7 py-3 md:py-3 w-full md:w-1/2 lg:w-1/4 text-base">
        <span class="@if($transaction->type == Transaction::EXPENSE_TYPE) text-red-500 @else text-green-500 @endif">
            {{ format_amount($transaction->amount, $transaction->type, $transaction->account->currency) }}
        </span>
    </td>
</tr>
