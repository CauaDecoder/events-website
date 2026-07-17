<main class="min-h-screen bg-zinc-50 p-6 dark:bg-zinc-900 lg:p-10">
    <div class="mx-auto max-w-7xl space-y-6">
        <div class="flex items-start justify-between gap-4">
            <div>
                <flux:heading size="xl">Painel do cliente</flux:heading>
                <flux:text class="mt-2">Gerencie seus eventos a partir deste painel.</flux:text>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <flux:button type="submit" variant="ghost" icon="arrow-right-start-on-rectangle">Sair</flux:button>
            </form>
        </div>

        <flux:callout icon="rocket-launch" heading="Tudo pronto para começar">
            A fundação do painel está configurada com Livewire e Flux UI.
        </flux:callout>
    </div>
</main>
