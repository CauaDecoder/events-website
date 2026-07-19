<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $title ?? config('app.name') }}</title>

        @vite(['resources/css/client.css', 'resources/js/app.js'])
        @livewireStyles
        @fluxAppearance
        <style>[x-cloak]{display:none!important}</style>
    </head>
    <body class="min-h-screen bg-[#f7f4f1] text-stone-950 antialiased dark:bg-zinc-950 dark:text-white">
        <x-page-loader />
        @php
            $sections = [
                ['key' => 'events', 'label' => 'Eventos', 'icon' => 'calendar-days', 'group' => 'Workspace'],
                ['key' => 'sites', 'label' => 'Sites', 'icon' => 'window', 'group' => 'Workspace'],
                ['key' => 'media', 'label' => 'Mídia', 'icon' => 'photo', 'group' => 'Workspace'],
                ['key' => 'domains', 'label' => 'Domínios', 'icon' => 'globe-alt', 'group' => 'Workspace'],
                ['key' => 'team', 'label' => 'Equipe', 'icon' => 'user-group', 'group' => 'Colaboração'],
                ['key' => 'settings', 'label' => 'Configurações', 'icon' => 'cog-6-tooth', 'group' => 'Conta'],
            ];
            $availableTenants = \App\Modules\Tenancy\Infrastructure\Models\Tenant::query()
                ->where(fn ($query) => $query->where('owner_user_id', auth()->id())->orWhereHas('members', fn ($members) => $members->where('user_id', auth()->id())->where('status', 'active')))
                ->orderBy('name')->get();
            $currentTenant = $availableTenants->firstWhere('id', (int) session('tenant_id')) ?? $availableTenants->first();
        @endphp
        <div x-data="{ sidebarCollapsed: localStorage.getItem('client-sidebar-collapsed') === 'true' }" x-init="$watch('sidebarCollapsed', value => localStorage.setItem('client-sidebar-collapsed', value))" x-bind:style="`grid-template-columns: ${sidebarCollapsed ? '5.25rem' : '17rem'} minmax(0, 1fr)`" class="min-h-screen transition-[grid-template-columns] duration-300 lg:grid">
            <aside class="hidden min-h-screen border-r border-stone-200 bg-white transition-all dark:border-white/10 dark:bg-zinc-900 lg:flex lg:flex-col">
                <div class="flex h-20 shrink-0 items-center border-b border-stone-100 px-4 dark:border-white/10" x-bind:class="sidebarCollapsed ? 'justify-center' : 'gap-3'">
                    <span class="grid size-10 shrink-0 place-items-center rounded-2xl bg-[#e58775] font-bold text-[#1c1917] shadow-[3px_3px_0_0_#1c1917] dark:shadow-[3px_3px_0_0_rgba(255,255,255,0.16)]">E</span>
                    <flux:dropdown x-show="!sidebarCollapsed" x-cloak class="min-w-0 flex-1" align="start"><button type="button" class="flex w-full min-w-0 items-center gap-2 text-left"><div class="min-w-0 flex-1"><p class="truncate text-sm font-semibold">{{ config('app.name') }}</p><p class="truncate text-[10px] text-stone-400">{{ $currentTenant?->name ?? 'Workspace' }}</p></div><flux:icon.chevron-up-down class="size-3.5 shrink-0 text-stone-400" /></button><flux:menu><div class="px-2 py-1.5 text-[10px] font-bold uppercase tracking-wider text-stone-400">Workspaces</div>@foreach($availableTenants as $tenant)<form method="POST" action="{{ route('client.tenants.switch', $tenant) }}">@csrf<flux:menu.item as="button" type="submit" icon="{{ $tenant->id === $currentTenant?->id ? 'check-circle' : 'building-office' }}">{{ $tenant->name }}</flux:menu.item></form>@endforeach</flux:menu></flux:dropdown>
                    <flux:dropdown x-show="!sidebarCollapsed" x-cloak align="end"><flux:button variant="ghost" size="sm" square aria-label="Alterar tema"><flux:icon.sun x-show="$flux.appearance === 'light'" class="size-4" /><flux:icon.moon x-show="$flux.appearance === 'dark'" class="size-4" /><flux:icon.computer-desktop x-show="$flux.appearance === 'system'" class="size-4" /></flux:button><flux:menu><flux:menu.item icon="sun" x-on:click="$flux.appearance = 'light'">Modo claro</flux:menu.item><flux:menu.item icon="moon" x-on:click="$flux.appearance = 'dark'">Modo escuro</flux:menu.item><flux:menu.item icon="computer-desktop" x-on:click="$flux.appearance = 'system'">Usar sistema</flux:menu.item></flux:menu></flux:dropdown>
                </div>
                <nav class="min-h-0 flex-1 overflow-y-auto p-3">
                    <a href="{{ route('client.dashboard') }}" wire:navigate title="Visão geral" x-bind:class="sidebarCollapsed ? 'justify-center px-0' : 'px-3'" @class(['relative mb-4 flex h-11 items-center gap-3 rounded-xl text-sm transition', 'bg-[#fde9e3] font-medium text-[#7d382f] dark:bg-[#e58775]/15 dark:text-[#f1ad9e]' => request()->routeIs('client.dashboard'), 'text-stone-600 hover:bg-stone-100 dark:text-stone-400 dark:hover:bg-white/5' => ! request()->routeIs('client.dashboard')])><flux:icon.home class="size-5 shrink-0" /><span x-show="!sidebarCollapsed" x-cloak>Visão geral</span>@if(request()->routeIs('client.dashboard'))<span class="absolute -left-3 h-6 w-1 rounded-r-full bg-[#e58775]"></span>@endif</a>
                    @foreach (collect($sections)->groupBy('group') as $group => $items)
                        <div class="mb-5"><div x-show="!sidebarCollapsed" x-cloak class="mb-2 px-3 text-[10px] font-bold uppercase tracking-[.16em] text-stone-400">{{ $group }}</div><div x-show="sidebarCollapsed" class="mx-auto mb-2 h-px w-7 bg-stone-200 dark:bg-white/10"></div><div class="space-y-1">@foreach($items as $item)@php($itemRoute = match($item['key']) { 'events' => route('client.events.index'), 'sites' => route('client.sites.index'), 'media' => route('client.media.index'), 'domains' => route('client.domains.index'), 'team' => route('client.team.index'), 'settings' => route('client.settings.index') })@php($itemActive = request()->routeIs("client.{$item['key']}.*") || ($item['key'] === 'events' && request()->routeIs('client.builder.*')))<a href="{{ $itemRoute }}" wire:navigate title="{{ $item['label'] }}" x-bind:class="sidebarCollapsed ? 'justify-center px-0' : 'px-3'" @class(['relative flex h-10 items-center gap-3 rounded-xl text-sm transition', 'bg-[#fde9e3] font-medium text-[#7d382f] dark:bg-[#e58775]/15 dark:text-[#f1ad9e]' => $itemActive, 'text-stone-600 hover:bg-stone-100 dark:text-stone-400 dark:hover:bg-white/5' => ! $itemActive])><flux:icon :name="$item['icon']" class="size-5 shrink-0" /><span x-show="!sidebarCollapsed" x-cloak class="truncate">{{ $item['label'] }}</span>@if($itemActive)<span class="absolute -left-3 h-5 w-1 rounded-r-full bg-[#e58775]"></span>@endif</a>@endforeach</div></div>
                    @endforeach
                </nav>
                <div class="shrink-0 border-t border-stone-100 p-3 dark:border-white/10">
                    <flux:dropdown position="top" align="start" class="w-full"><button type="button" title="Menu da conta" x-bind:class="sidebarCollapsed ? 'justify-center px-0' : 'px-2'" class="flex h-12 w-full items-center gap-3 rounded-xl text-left transition hover:bg-stone-100 dark:hover:bg-white/5"><flux:avatar size="sm" :name="auth()->user()->name" class="shrink-0" /><div x-show="!sidebarCollapsed" x-cloak class="min-w-0 flex-1"><div class="truncate text-sm font-medium">{{ auth()->user()->name }}</div><div class="truncate text-[11px] text-stone-500">{{ auth()->user()->plan === 'premium' ? 'Plano Premium' : 'Plano Gratuito' }}</div></div><flux:icon.chevron-up x-show="!sidebarCollapsed" x-cloak class="size-4 text-stone-400" /></button><flux:menu><div class="px-2 py-1.5"><p class="max-w-56 truncate text-sm font-medium">{{ auth()->user()->name }}</p><p class="max-w-56 truncate text-xs text-stone-500">{{ auth()->user()->email }}</p></div><flux:menu.separator /><flux:menu.item icon="cog-6-tooth" :href="route('client.settings.index')" wire:navigate>Configurações</flux:menu.item><flux:menu.item icon="sun" x-on:click="$flux.appearance = $flux.dark ? 'light' : 'dark'">Alternar aparência</flux:menu.item><flux:menu.separator /><form method="POST" action="{{ route('logout') }}">@csrf<flux:menu.item as="button" type="submit" variant="danger" icon="arrow-right-start-on-rectangle">Sair</flux:menu.item></form></flux:menu></flux:dropdown>
                    <button type="button" x-on:click="sidebarCollapsed = !sidebarCollapsed" class="mt-2 flex h-9 w-full items-center justify-center gap-2 rounded-xl text-xs font-medium text-stone-400 transition hover:bg-stone-100 hover:text-stone-700 dark:hover:bg-white/5 dark:hover:text-stone-200" x-bind:title="sidebarCollapsed ? 'Expandir menu' : 'Recolher menu'"><flux:icon.chevron-left class="size-4 transition-transform duration-300" x-bind:class="sidebarCollapsed && 'rotate-180'" /><span x-show="!sidebarCollapsed" x-cloak>Recolher menu</span></button>
                </div>
            </aside>
            <div class="min-w-0" data-page-transition>
                <header class="sticky top-0 z-30 border-b border-stone-200 bg-white/95 px-4 py-3 backdrop-blur dark:border-white/10 dark:bg-zinc-900/95 lg:hidden">
                    <div class="mb-3 flex items-center justify-between"><span class="flex items-center gap-2 font-semibold"><span class="grid size-8 place-items-center rounded-xl bg-[#e58775] text-[#1c1917]">E</span>{{ config('app.name') }}</span><div class="flex items-center gap-1"><flux:dropdown align="end"><flux:button variant="ghost" size="sm" square aria-label="Alterar tema"><flux:icon.sun x-show="$flux.appearance === 'light'" class="size-4" /><flux:icon.moon x-show="$flux.appearance === 'dark'" class="size-4" /><flux:icon.computer-desktop x-show="$flux.appearance === 'system'" class="size-4" /></flux:button><flux:menu><flux:menu.item icon="sun" x-on:click="$flux.appearance = 'light'">Modo claro</flux:menu.item><flux:menu.item icon="moon" x-on:click="$flux.appearance = 'dark'">Modo escuro</flux:menu.item><flux:menu.item icon="computer-desktop" x-on:click="$flux.appearance = 'system'">Usar sistema</flux:menu.item></flux:menu></flux:dropdown><flux:avatar size="xs" :name="auth()->user()->name" /></div></div>
                    <nav class="flex gap-1 overflow-x-auto pb-1"><flux:button size="sm" variant="ghost" icon="home" :href="route('client.dashboard')" wire:navigate>Início</flux:button>@foreach (array_slice($sections, 0, 4) as $item)@php($mobileRoute = match($item['key']) { 'events' => route('client.events.index'), 'sites' => route('client.sites.index'), 'media' => route('client.media.index'), 'domains' => route('client.domains.index'), 'team' => route('client.team.index'), 'settings' => route('client.settings.index') })<flux:button size="sm" variant="ghost" :icon="$item['icon']" :href="$mobileRoute" wire:navigate>{{ $item['label'] }}</flux:button>@endforeach</nav>
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
