<main class="p-5 sm:p-8 lg:p-10">
    <div class="mx-auto max-w-7xl space-y-8">
        <div><flux:text class="mb-1 text-[#b85240] dark:text-[#f1ad9e]">Olá, {{ str(auth()->user()->name)->before(' ') }}</flux:text><flux:heading size="xl">Vamos criar algo especial?</flux:heading><flux:text class="mt-2">Seu espaço para organizar eventos e publicar experiências memoráveis.</flux:text></div>
        <div class="relative overflow-hidden rounded-3xl bg-[#1c1917] p-7 text-white sm:p-9">
            <div class="absolute -right-16 -top-20 size-64 rounded-full border-[32px] border-[#e58775]"></div>
            <div class="relative max-w-xl"><span class="text-xs font-semibold uppercase tracking-[0.14em] text-[#e58775]">Primeiro passo</span><h2 class="mt-3 text-2xl font-semibold sm:text-3xl">Crie seu primeiro evento</h2><p class="mt-3 max-w-md text-sm leading-6 text-stone-400">Organize as informações essenciais e prepare o espaço onde o site será construído.</p><flux:button class="mt-6" variant="primary" icon:trailing="arrow-right" :href="route('client.section', 'events')" wire:navigate>Ir para eventos</flux:button></div>
        </div>
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            @foreach ([['Sites', 'window', 'sites'], ['Mídia', 'photo', 'media'], ['Domínios', 'globe-alt', 'domains']] as [$label, $icon, $section])
                <a href="{{ route('client.section', $section) }}" wire:navigate class="group rounded-2xl border border-stone-200 bg-white p-5 transition hover:-translate-y-0.5 hover:border-[#e58775] hover:shadow-lg dark:border-white/10 dark:bg-zinc-900"><span class="mb-5 grid size-11 place-items-center rounded-2xl bg-[#fde9e3] text-[#b85240] dark:bg-[#e58775]/15 dark:text-[#f1ad9e]"><flux:icon :name="$icon" class="size-5" /></span><div class="flex items-center justify-between"><span class="font-medium">{{ $label }}</span><flux:icon.arrow-right class="size-4 text-stone-400 transition group-hover:translate-x-1 group-hover:text-[#b85240]" /></div></a>
            @endforeach
        </div>
    </div>
</main>
