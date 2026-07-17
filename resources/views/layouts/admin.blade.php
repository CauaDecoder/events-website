<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $title ?? config('app.name') }}</title>

        @vite(['resources/css/admin.css', 'resources/js/app.js'])
        @livewireStyles
        @fluxAppearance
    </head>
    <body class="min-h-screen bg-stone-100 text-stone-950 antialiased dark:bg-zinc-950 dark:text-white">
        <div class="min-h-screen lg:grid lg:grid-cols-[17rem_1fr]">
            <aside class="hidden border-r border-white/10 bg-[#1c1917] text-white lg:flex lg:flex-col">
                <div class="flex h-20 items-center gap-3 border-b border-white/10 px-6">
                    <span class="grid size-10 place-items-center rounded-2xl bg-[#e58775] font-bold text-[#1c1917]">E</span>
                    <div><div class="font-semibold">{{ config('app.name') }}</div><div class="text-xs text-stone-400">Administração</div></div>
                </div>
                <nav class="flex-1 space-y-1 p-4">
                    <a href="{{ route('admin.dashboard') }}" wire:navigate @class(['flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm transition', 'bg-[#e58775] font-medium text-[#1c1917]' => request()->routeIs('admin.dashboard'), 'text-stone-300 hover:bg-white/5 hover:text-white' => ! request()->routeIs('admin.dashboard')])><flux:icon.squares-2x2 class="size-5" /> Dashboard</a>
                    <a href="{{ route('admin.users.index') }}" wire:navigate @class(['flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm transition', 'bg-[#e58775] font-medium text-[#1c1917]' => request()->routeIs('admin.users.*'), 'text-stone-300 hover:bg-white/5 hover:text-white' => ! request()->routeIs('admin.users.*')])><flux:icon.users class="size-5" /> Usuários</a>
                    <a href="{{ route('client.dashboard') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm text-stone-300 transition hover:bg-white/5 hover:text-white"><flux:icon.arrow-top-right-on-square class="size-5" /> Painel do cliente</a>
                </nav>
                <div class="border-t border-white/10 p-4">
                    <div class="mb-3 flex items-center gap-3 px-2"><flux:avatar size="sm" :name="auth()->user()->name" /><div class="min-w-0"><div class="truncate text-sm font-medium">{{ auth()->user()->name }}</div><div class="truncate text-xs text-stone-500">{{ auth()->user()->email }}</div></div></div>
                    <form method="POST" action="{{ route('logout') }}">@csrf <button class="flex w-full items-center gap-3 rounded-xl px-3 py-2 text-sm text-stone-400 transition hover:bg-white/5 hover:text-white"><flux:icon.arrow-right-start-on-rectangle class="size-5" /> Sair</button></form>
                </div>
            </aside>
            <div class="min-w-0">
                <header class="sticky top-0 z-30 flex h-16 items-center justify-between border-b border-stone-200 bg-white/95 px-4 backdrop-blur dark:border-white/10 dark:bg-zinc-900/95 lg:hidden">
                    <span class="flex items-center gap-2 font-semibold"><span class="grid size-8 place-items-center rounded-xl bg-[#e58775] text-[#1c1917]">E</span> Admin</span>
                    <nav class="flex gap-1"><flux:button size="sm" variant="ghost" icon="squares-2x2" :href="route('admin.dashboard')" wire:navigate /><flux:button size="sm" variant="ghost" icon="users" :href="route('admin.users.index')" wire:navigate /></nav>
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
