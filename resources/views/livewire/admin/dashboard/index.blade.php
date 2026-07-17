<main class="min-h-screen bg-zinc-50 p-6 dark:bg-zinc-900 lg:p-10">
    <div class="mx-auto max-w-7xl space-y-6">
        <div class="flex items-start justify-between gap-4">
            <div>
                <flux:heading size="xl">Administração</flux:heading>
                <flux:text class="mt-2">Base inicial do painel administrativo.</flux:text>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <flux:button type="submit" variant="ghost" icon="arrow-right-start-on-rectangle">Sair</flux:button>
            </form>
        </div>

        <flux:callout icon="information-circle" heading="Projeto pronto para evoluir">
            Os módulos de negócio ainda não possuem funcionalidades implementadas.
        </flux:callout>
    </div>
</main>
