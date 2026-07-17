<?php

declare(strict_types=1);

namespace App\Livewire\Auth;

use App\Livewire\Forms\Users\RegisterForm;
use App\Modules\Identity\Application\Services\RegisterUserService;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.auth')]
#[Title('Criar conta')]
final class Register extends Component
{
    public RegisterForm $form;

    public function register(RegisterUserService $service): void
    {
        $this->form->validate();
        $user = $service->execute($this->form->toDto());

        Auth::login($user);
        session()->regenerate();

        Flux::toast(
            heading: 'Conta criada com sucesso',
            text: 'Agora confirme o endereço enviado para seu e-mail.',
            variant: 'success',
        );

        $this->redirectRoute('verification.notice', navigate: true);
    }

    public function render(): View
    {
        return view('livewire.auth.register');
    }
}
