<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $title ?? config('app.name') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
        @fluxAppearance
    </head>
    <body class="min-h-screen bg-[#f7f4f1] antialiased dark:bg-zinc-950">
        <x-page-loader />
        <div data-page-transition class="grid min-h-screen lg:grid-cols-[minmax(23rem,0.88fr)_1.35fr]">
            <aside class="relative hidden overflow-hidden bg-[#1c1917] p-12 text-white lg:flex lg:flex-col lg:justify-between xl:p-16">
                <div aria-hidden="true" class="pointer-events-none absolute inset-0 overflow-hidden">
                    <div class="auth-orbit absolute -right-28 -top-24 size-80 rounded-full border-[44px] border-[#e58775]"></div>
                    <div class="auth-orbit-delayed absolute right-14 top-44 size-20 rotate-12 rounded-2xl border-2 border-white/25"></div>
                    <div class="absolute -bottom-28 -left-24 size-80 rounded-full border border-white/15"></div>
                    <div class="absolute bottom-28 left-20 size-5 rounded-full bg-[#e58775]"></div>
                    <div class="absolute bottom-16 right-16 grid grid-cols-4 gap-3 opacity-30">
                        @for ($dot = 0; $dot < 16; $dot++)
                            <span class="size-1 rounded-full bg-white"></span>
                        @endfor
                    </div>
                </div>

                <a href="{{ url('/') }}" class="relative z-10 inline-flex items-center gap-3 font-semibold tracking-tight">
                    <span class="grid size-11 place-items-center rounded-2xl bg-[#e58775] text-[#1c1917] shadow-[6px_6px_0_0_rgba(255,255,255,0.13)]">
                        <svg viewBox="0 0 24 24" fill="none" class="size-6" aria-hidden="true">
                            <path d="M6 3v3M18 3v3M4 9h16M6.5 13h3M6.5 17h5M5 5h14a1 1 0 0 1 1 1v14H4V6a1 1 0 0 1 1-1Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>
                    <span class="text-lg">{{ config('app.name') }}</span>
                </a>

                <div class="relative z-10 max-w-md pb-10">
                    <span class="mb-6 block h-1 w-14 rounded-full bg-[#e58775]"></span>
                    <p class="text-4xl font-semibold leading-[1.08] tracking-[-0.04em] xl:text-5xl">
                        Seu evento merece um lugar inesquecível.
                    </p>
                    <p class="mt-6 max-w-sm text-base leading-7 text-stone-300">
                        Crie, publique e gerencie experiências digitais para cada momento especial.
                    </p>
                </div>

                <p class="relative z-10 text-sm text-stone-500">Sites para eventos, feitos com cuidado.</p>
            </aside>

            <main class="relative flex min-h-screen items-center justify-center overflow-hidden px-5 py-10 sm:px-8 lg:px-12">
                <div aria-hidden="true" class="pointer-events-none absolute inset-0">
                    <div class="absolute left-8 top-8 size-24 rounded-full border border-[#e58775]/35 sm:left-16 sm:top-14"></div>
                    <div class="auth-orbit absolute -right-12 top-[18%] size-32 rounded-full bg-[#e58775]/10"></div>
                    <div class="absolute bottom-16 left-[12%] size-12 rotate-12 rounded-xl border-2 border-stone-300/60 dark:border-white/10"></div>
                    <div class="absolute inset-x-0 top-24 border-t border-stone-200/60 dark:border-white/5"></div>
                    <div class="absolute bottom-24 left-0 right-0 border-t border-stone-200/60 dark:border-white/5"></div>
                </div>

                <div class="relative z-10 w-full max-w-[29rem]">
                    <a href="{{ url('/') }}" class="mb-8 inline-flex items-center gap-2.5 font-semibold text-stone-900 dark:text-white lg:hidden">
                        <span class="grid size-9 place-items-center rounded-xl bg-[#e58775] text-[#1c1917]">
                            <span class="text-lg">E</span>
                        </span>
                        {{ config('app.name') }}
                    </a>

                    {{ $slot }}
                </div>
            </main>
        </div>

        @persist('toast')
            <flux:toast.group position="top end">
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @livewireScripts
        @fluxScripts
    </body>
</html>
