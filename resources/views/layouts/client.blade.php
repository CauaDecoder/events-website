<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $title ?? config('app.name') }}</title>

        @vite(['resources/css/client.css', 'resources/js/app.js'])
        @livewireStyles
        @fluxAppearance
    </head>
    <body class="min-h-screen bg-[#f7f4f1] text-stone-950 antialiased dark:bg-zinc-950 dark:text-white">
        @php
            $sections = [
                ['key' => 'events', 'label' => 'Eventos', 'icon' => 'calendar-days'],
                ['key' => 'sites', 'label' => 'Sites', 'icon' => 'window'],
                ['key' => 'media', 'label' => 'Mídia', 'icon' => 'photo'],
                ['key' => 'domains', 'label' => 'Domínios', 'icon' => 'globe-alt'],
                ['key' => 'team', 'label' => 'Equipe', 'icon' => 'user-group'],
                ['key' => 'settings', 'label' => 'Configurações', 'icon' => 'cog-6-tooth'],
            ];
        @endphp
        <div class="min-h-screen lg:grid lg:grid-cols-[17rem_1fr]">
            <aside class="hidden border-r border-stone-200 bg-white dark:border-white/10 dark:bg-zinc-900 lg:flex lg:flex-col">
                <div class="flex h-20 items-center gap-3 border-b border-stone-100 px-6 dark:border-white/10"><span class="grid size-10 place-items-center rounded-2xl bg-[#e58775] font-bold text-[#1c1917] shadow-[4px_4px_0_0_#1c1917]">E</span><span class="font-semibold">{{ config('app.name') }}</span></div>
                <nav class="flex-1 space-y-1 p-4">
                    <a href="{{ route('client.dashboard') }}" wire:navigate @class(['flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm transition', 'bg-[#fde9e3] font-medium text-[#7d382f] dark:bg-[#e58775]/15 dark:text-[#f1ad9e]' => request()->routeIs('client.dashboard'), 'text-stone-600 hover:bg-stone-100 dark:text-stone-400 dark:hover:bg-white/5' => ! request()->routeIs('client.dashboard')])><flux:icon.home class="size-5" /> Visão geral</a>
                    @foreach ($sections as $item)
                        <a href="{{ route('client.section', $item['key']) }}" wire:navigate @class(['flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm transition', 'bg-[#fde9e3] font-medium text-[#7d382f] dark:bg-[#e58775]/15 dark:text-[#f1ad9e]' => request()->route('section') === $item['key'], 'text-stone-600 hover:bg-stone-100 dark:text-stone-400 dark:hover:bg-white/5' => request()->route('section') !== $item['key']])><flux:icon :name="$item['icon']" class="size-5" /> {{ $item['label'] }}</a>
                    @endforeach
                </nav>
                <div class="border-t border-stone-100 p-4 dark:border-white/10">
                    <div class="mb-3 flex items-center gap-3 px-2"><flux:avatar size="sm" :name="auth()->user()->name" /><div class="min-w-0"><div class="truncate text-sm font-medium">{{ auth()->user()->name }}</div><div class="truncate text-xs text-stone-500">{{ auth()->user()->email }}</div></div></div>
                    <form method="POST" action="{{ route('logout') }}">@csrf <button class="flex w-full items-center gap-3 rounded-xl px-3 py-2 text-sm text-stone-500 transition hover:bg-stone-100 dark:hover:bg-white/5"><flux:icon.arrow-right-start-on-rectangle class="size-5" /> Sair</button></form>
                </div>
            </aside>
            <div class="min-w-0">
                <header class="sticky top-0 z-30 border-b border-stone-200 bg-white/95 px-4 py-3 backdrop-blur dark:border-white/10 dark:bg-zinc-900/95 lg:hidden">
                    <div class="mb-3 flex items-center justify-between"><span class="flex items-center gap-2 font-semibold"><span class="grid size-8 place-items-center rounded-xl bg-[#e58775] text-[#1c1917]">E</span>{{ config('app.name') }}</span><flux:avatar size="xs" :name="auth()->user()->name" /></div>
                    <nav class="flex gap-1 overflow-x-auto pb-1"><flux:button size="sm" variant="ghost" icon="home" :href="route('client.dashboard')" wire:navigate>Início</flux:button>@foreach (array_slice($sections, 0, 4) as $item)<flux:button size="sm" variant="ghost" :icon="$item['icon']" :href="route('client.section', $item['key'])" wire:navigate>{{ $item['label'] }}</flux:button>@endforeach</nav>
                </header>
                {{ $slot }}
            </div>
        </div>

        @persist('toast')
            <flux:toast.group position="top end"><flux:toast /></flux:toast.group>
        @endpersist

        @livewireScripts
        @fluxScripts
    </body>
</html>
