<flux:card class="auth-card space-y-7 text-center">
    <span class="mx-auto grid size-14 place-items-center rounded-2xl bg-brand-100 text-brand-700 dark:bg-brand-400/15 dark:text-brand-300">
        <flux:icon.envelope class="size-6" />
    </span>
    <div>
        <flux:heading size="xl">Verifique seu e-mail</flux:heading>
        <flux:text class="mt-2">Enviamos um link de confirmação para o endereço cadastrado.</flux:text>
    </div>

    @if ($sent)
        <flux:callout variant="success">Um novo link de verificação foi enviado.</flux:callout>
    @endif

    <flux:button wire:click="resend" variant="primary" class="w-full">Reenviar e-mail</flux:button>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <flux:button type="submit" variant="ghost">Sair</flux:button>
    </form>
</flux:card>
