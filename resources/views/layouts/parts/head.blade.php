<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width"/>
    <title>{{ $title }}</title>
    <meta name="theme-color" content="#6B7280"/>
    @if(app()->hasDebugModeEnabled())
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.4/flowbite.min.css" rel="stylesheet"/>
    @endif
    @vite('resources/css/app.css')
</head>
<body>
