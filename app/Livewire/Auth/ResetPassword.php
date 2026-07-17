<?php

declare(strict_types=1);

namespace App\Livewire\Auth;

use App\Livewire\Forms\Users\ResetPasswordForm;
use App\Modules\Identity\Application\Services\ResetPasswordService;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.auth')]
#[Title('Redefinir senha')]
final class ResetPassword extends Component
{
    public ResetPasswordForm $form;

    public function mount(string $token): void
    {
        $this->form->token = $token;
        $this->form->email = (string) request()->query('email');
    }

    public function resetPassword(ResetPasswordService $service): void
    {
        $this->form->validate();

        try {
            $service->execute($this->form->toDto());
        } catch (ValidationException $exception) {
            $message = $exception->errors()['email'][0] ?? 'O link informado é inválido ou expirou.';
            $this->addError('form.email', $message);
            Flux::toast(heading: 'Não foi possível redefinir', text: $message, variant: 'danger');

            return;
        }

        session()->flash('status', __('passwords.reset'));
        Flux::toast(heading: 'Senha redefinida', text: 'Você já pode entrar com a nova senha.', variant: 'success');
        $this->redirectRoute('login', navigate: true);
    }

    public function render(): View
    {
        return view('livewire.auth.reset-password');
    }
}
