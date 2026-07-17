<flux:card class="auth-card space-y-7">
    <div>
        <span class="mb-3 inline-flex rounded-full bg-brand-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.14em] text-brand-700 dark:bg-brand-400/15 dark:text-brand-300">Bem-vindo</span>
        <flux:heading size="xl" class="tracking-tight">Entre na sua conta</flux:heading>
        <flux:text class="mt-2">Continue criando experiências memoráveis.</flux:text>
    </div>

    @if (session('status'))
        <flux:callout variant="success">{{ session('status') }}</flux:callout>
    @endif

    <form wire:submit="authenticate" class="space-y-5">
        <flux:input wire:model="form.email" type="email" label="E-mail" autocomplete="email" required />
        <flux:input wire:model="form.password" type="password" label="Senha" autocomplete="current-password" required viewable />

        <div class="flex items-center justify-between">
            <flux:checkbox wire:model="form.remember" label="Lembrar de mim" />
            <flux:link :href="route('password.request')" wire:navigate>Esqueci minha senha</flux:link>
        </div>

        <flux:button type="submit" variant="primary" icon:trailing="arrow-right" class="w-full">Entrar</flux:button>
    </form>

    <flux:text class="text-center">
        Ainda não possui uma conta?
        <flux:link :href="route('register')" wire:navigate>Cadastre-se</flux:link>
    </flux:text>
</flux:card>
