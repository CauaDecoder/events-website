<flux:card class="auth-card space-y-7">
    <div>
        <span class="mb-3 grid size-11 place-items-center rounded-2xl bg-brand-100 text-brand-700 dark:bg-brand-400/15 dark:text-brand-300">
            <flux:icon.shield-check class="size-5" />
        </span>
        <flux:heading size="xl">Confirme sua senha</flux:heading>
        <flux:text class="mt-2">Esta é uma área protegida. Confirme sua senha para continuar.</flux:text>
    </div>

    <form wire:submit="confirm" class="space-y-5">
        <flux:input wire:model="form.password" type="password" label="Senha" autocomplete="current-password" required viewable autofocus />
        <flux:button type="submit" variant="primary" icon:trailing="arrow-right" class="w-full">Confirmar</flux:button>
    </form>
</flux:card>
