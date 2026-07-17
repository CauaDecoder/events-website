<flux:card class="auth-card space-y-7">
    <div>
        <span class="mb-3 grid size-11 place-items-center rounded-2xl bg-brand-100 text-brand-700 dark:bg-brand-400/15 dark:text-brand-300">
            <flux:icon.key class="size-5" />
        </span>
        <flux:heading size="xl">Recuperar senha</flux:heading>
        <flux:text class="mt-2">Enviaremos um link de redefinição para seu e-mail.</flux:text>
    </div>

    @if ($status)
        <flux:callout variant="success">{{ $status }}</flux:callout>
    @endif

    <form wire:submit="sendResetLink" class="space-y-5">
        <flux:input wire:model="form.email" type="email" label="E-mail" autocomplete="email" required autofocus />
        <flux:button type="submit" variant="primary" icon:trailing="paper-airplane" class="w-full">Enviar link</flux:button>
    </form>

    <flux:link :href="route('login')" wire:navigate class="block text-center">Voltar para o login</flux:link>
</flux:card>
