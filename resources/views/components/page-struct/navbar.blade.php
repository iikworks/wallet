<nav class="bg-gray-500 fixed w-full z-40 h-14 text-gray-200 flex items-center">
    <x-page-struct.container :classes="'flex justify-between items-center'">
        <a href="/" class="text-2xl font-medium">Кошелёк</a>
        <div class="relative flex">
            <a href="#"
               class="flex text-sm items-center gap-2 font-medium px-4 h-14 hover:bg-black/20 transition">
                <img class="h-8 w-8 rounded-full"
                     src="https://sun2.beltelecom-by-minsk.userapi.com/s/v1/ig2/DgrcYQCirFfzcPdGSRrQvZVqVLyR8OL1ZQO5V4_0CFt7q0bgXf79bN6wLCvsoojymEAbIrbg2jz6xtGWF3bFJoj4.jpg?size=400x400&amp;quality=95&amp;crop=379,1214,946,946&amp;ava=1"
                     alt="User"/>
                {{ $user->first_name }}
            </a>
            <a href="{{ route('logout') }}"
               class="flex text-sm items-center gap-2 font-medium px-4 h-14 hover:bg-black/20 transition">
                {{ __('auth.make_logout') }}
            </a>
        </div>
    </x-page-struct.container>
</nav>
