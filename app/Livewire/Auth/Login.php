<?php

declare(strict_types=1);

namespace App\Livewire\Auth;

use App\Livewire\Forms\Users\LoginForm;
use App\Modules\Identity\Application\Services\AuthenticateUserService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
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
        $user = $service->execute($this->form->toDto());

        Auth::login($user, $this->form->remember);
        session()->regenerate();

        $this->redirectIntended(default: route('client.dashboard'), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.auth.login');
    }
}
