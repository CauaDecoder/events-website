<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>{{ $title ?? 'Builder' }}</title>@vite(['resources/css/client.css', 'resources/js/app.js'])@livewireStyles @fluxAppearance</head>
<body class="h-screen overflow-hidden bg-stone-100 text-stone-950 antialiased dark:bg-zinc-950 dark:text-white">
    <x-page-loader />
    <div data-page-transition class="h-full">{{ $slot }}</div>
    @persist('toast')<flux:toast.group position="top end"><flux:toast /></flux:toast.group>@endpersist
    @livewireScripts @fluxScripts
</body>
</html>
