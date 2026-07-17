<?php

declare(strict_types=1);

namespace App\Livewire\Auth;

use App\Livewire\Forms\Users\ForgotPasswordForm;
use App\Modules\Identity\Application\Services\SendPasswordResetLinkService;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.auth')]
#[Title('Recuperar senha')]
final class ForgotPassword extends Component
{
    public ForgotPasswordForm $form;

    public ?string $status = null;

    public function sendResetLink(SendPasswordResetLinkService $service): void
    {
        $this->form->validate();

        try {
            $this->status = $service->execute($this->form->email);
        } catch (ValidationException $exception) {
            $message = $exception->errors()['email'][0] ?? 'Não foi possível enviar o link.';
            $this->addError('form.email', $message);
            Flux::toast(heading: 'Falha no envio', text: $message, variant: 'danger');

            return;
        }

        Flux::toast(heading: 'E-mail enviado', text: $this->status, variant: 'success');
        $this->form->reset();
    }

    public function render(): View
    {
        return view('livewire.auth.forgot-password');
    }
}
