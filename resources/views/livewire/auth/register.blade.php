<flux:card class="auth-card space-y-7">
    <div>
        <span class="mb-3 inline-flex rounded-full bg-brand-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.14em] text-brand-700 dark:bg-brand-400/15 dark:text-brand-300">Comece agora</span>
        <flux:heading size="xl" class="tracking-tight">Crie sua conta</flux:heading>
        <flux:text class="mt-2">Comece a criar sites para seus eventos.</flux:text>
    </div>

    <form wire:submit="register" class="space-y-5">
        <flux:input wire:model="form.name" label="Nome" autocomplete="name" required autofocus />
        <flux:input wire:model="form.email" type="email" label="E-mail" autocomplete="email" required />
        <flux:input wire:model="form.password" type="password" label="Senha" autocomplete="new-password" required viewable />
        <flux:input wire:model="form.password_confirmation" type="password" label="Confirme a senha" autocomplete="new-password" required viewable />

        <flux:button type="submit" variant="primary" icon:trailing="arrow-right" class="w-full">Criar conta</flux:button>
    </form>

    <flux:text class="text-center">
        Já possui uma conta?
        <flux:link :href="route('login')" wire:navigate>Entrar</flux:link>
    </flux:text>
</flux:card>
