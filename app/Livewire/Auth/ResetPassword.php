<?php

declare(strict_types=1);

namespace App\Livewire\Auth;

use App\Livewire\Forms\Users\ResetPasswordForm;
use App\Modules\Identity\Application\Services\ResetPasswordService;
use Illuminate\Contracts\View\View;
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
        $service->execute($this->form->toDto());

        session()->flash('status', __('passwords.reset'));
        $this->redirectRoute('login', navigate: true);
    }

    public function render(): View
    {
        return view('livewire.auth.reset-password');
    }
}
