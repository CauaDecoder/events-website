<flux:card class="auth-card space-y-7">
    <div>
        <span class="mb-3 grid size-11 place-items-center rounded-2xl bg-brand-100 text-brand-700 dark:bg-brand-400/15 dark:text-brand-300">
            <flux:icon.lock-closed class="size-5" />
        </span>
        <flux:heading size="xl">Redefinir senha</flux:heading>
        <flux:text class="mt-2">Escolha uma nova senha para sua conta.</flux:text>
    </div>

    <form wire:submit="resetPassword" class="space-y-5">
        <flux:input wire:model="form.email" type="email" label="E-mail" autocomplete="email" required />
        <flux:input wire:model="form.password" type="password" label="Nova senha" autocomplete="new-password" required viewable />
        <flux:input wire:model="form.password_confirmation" type="password" label="Confirme a senha" autocomplete="new-password" required viewable />
        <flux:button type="submit" variant="primary" icon:trailing="check" class="w-full">Redefinir senha</flux:button>
    </form>
</flux:card>
