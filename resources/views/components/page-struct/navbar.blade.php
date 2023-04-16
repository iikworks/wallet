@php use Illuminate\Support\Facades\Vite; @endphp
<nav class="bg-gray-500 fixed w-full z-40 h-14 text-gray-200 flex items-center">
    <x-page-struct.container :classes="'flex justify-between items-center'">
        <div class="flex items-center gap-10">
            <a href="/" class="text-2xl font-medium">{{ __('main.wallet') }}</a>
            @if(auth()->user()->is_admin)
                <div class="flex font-medium">
                    <a href="{{ route('organizations') }}"
                       class="flex items-center h-14 px-4 hover:bg-black/20 transition">{{ __('organizations.title') }}</a>
                    <a href="{{ route('banks') }}"
                       class="flex items-center h-14 px-4 hover:bg-black/20 transition">{{ __('banks.title') }}</a>
                </div>
            @endif
        </div>
        <div class="relative flex">
            <a href="#"
               class="flex items-center gap-2 font-medium px-4 h-14 hover:bg-black/20 transition">
                <img class="h-8 w-8 rounded-full"
                     src="{{ Vite::asset('resources/images/user.png') }}"
                     alt="User"/>
                {{ $user->first_name }}
            </a>
            <a href="{{ route('logout') }}"
               class="flex items-center gap-2 font-medium px-4 h-14 hover:bg-black/20 transition">
                {{ __('auth.make_logout') }}
            </a>
        </div>
    </x-page-struct.container>
</nav>
