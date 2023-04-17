@livewireScripts
@vite('resources/js/app.js')
@if(app()->hasDebugModeEnabled())
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.4/flowbite.min.js"></script>
    @endif

    @yield('scripts')
    </body>
    </html>
