@include('layouts.parts.head')
<div class="max-w-md mx-auto min-h-full py-12">
    <div class="bg-white shadow-sm rounded-xl py-4 px-5">
        @yield('content')
    </div>
</div>
@yield('scripts')
@include('layouts.parts.footer')
