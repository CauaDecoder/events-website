<main class="p-5 sm:p-8 lg:p-10">
    <div class="mx-auto max-w-7xl space-y-8">
        <div><flux:heading size="xl">Visão geral</flux:heading><flux:text class="mt-2">Acompanhe os principais números da plataforma.</flux:text></div>
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ([
                ['label' => 'Usuários', 'value' => $this->metrics['users'], 'icon' => 'users'],
                ['label' => 'Verificados', 'value' => $this->metrics['verified_users'], 'icon' => 'check-badge'],
                ['label' => 'Novos no mês', 'value' => $this->metrics['new_users_this_month'], 'icon' => 'user-plus'],
                ['label' => 'Tokens ativos', 'value' => $this->metrics['active_api_tokens'], 'icon' => 'key'],
            ] as $metric)
                <div class="rounded-2xl border border-stone-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-zinc-900">
                    <div class="mb-5 flex items-center justify-between"><flux:text>{{ $metric['label'] }}</flux:text><span class="grid size-10 place-items-center rounded-xl bg-[#fde9e3] text-[#b85240] dark:bg-[#e58775]/15 dark:text-[#f1ad9e]"><flux:icon :name="$metric['icon']" class="size-5" /></span></div>
                    <div class="text-3xl font-semibold tracking-tight">{{ number_format($metric['value'], 0, ',', '.') }}</div>
                </div>
            @endforeach
        </div>
        <div class="rounded-3xl bg-[#1c1917] p-7 text-white">
            <div class="max-w-xl"><span class="text-xs font-semibold uppercase tracking-[0.14em] text-[#e58775]">Administração</span><h2 class="mt-3 text-2xl font-semibold">A plataforma está pronta para crescer.</h2><p class="mt-2 text-sm leading-6 text-stone-400">Use a área de usuários para acompanhar as novas contas. Os próximos módulos serão conectados a este dashboard conforme forem implementados.</p></div>
        </div>
    </div>
</main>
