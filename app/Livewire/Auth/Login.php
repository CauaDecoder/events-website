<?php

declare(strict_types=1);

namespace App\Livewire\Auth;

use App\Livewire\Forms\Users\LoginForm;
use App\Modules\Identity\Application\Services\AuthenticateUserService;
use App\Support\Tenancy\TenantContext;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.auth')]
#[Title('Entrar')]
final class Login extends Component
{
    public LoginForm $form;

    public function authenticate(AuthenticateUserService $service): void
    {
        $this->form->validate();

        try {
            $user = $service->execute($this->form->toDto());
        } catch (ValidationException $exception) {
            $message = $exception->errors()['email'][0] ?? 'Não foi possível entrar com essas credenciais.';
            $this->addError('form.email', $message);
            $this->form->password = '';

            Flux::toast(
                heading: 'Não foi possível entrar',
                text: 'Confira seu e-mail e sua senha e tente novamente.',
                variant: 'danger',
            );

            return;
        }

        Auth::login($user, $this->form->remember);
        session()->regenerate();
        $tenantId = 0;
        if (! $user->hasRole('super-admin')) {
            $tenantId = app(TenantContext::class)->resolveFor($user)->id;
        }
        session()->put('tenant_id', $tenantId);
        setPermissionsTeamId($tenantId);
        $user->unsetRelation('roles')->unsetRelation('permissions');

        $destination = $user->hasRole('super-admin')
            ? route('admin.dashboard')
            : route('client.dashboard');

        $this->redirectIntended(default: $destination, navigate: true);
    }

    public function render(): View
    {
        return view('livewire.auth.login');
    }
}
