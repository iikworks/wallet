@include('layouts.parts.head')
<div>
    <x-page-struct.navbar/>
    <x-page-struct.container :classes="'pt-24'">
        @yield('content')
    </x-page-struct.container>
    <div class="container mt-8 pb-5 text-gray-500 max-w-sm pt-4 mx-auto text-center border-t-2">
        © 2022-2023,
        <span class="font-medium text-gray-700">NalikCo</span>
    </div>
</div>
@include('layouts.parts.footer')
