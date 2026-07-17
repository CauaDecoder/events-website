<?php

declare(strict_types=1);

namespace App\Livewire\Auth;

use App\Livewire\Forms\Users\ForgotPasswordForm;
use App\Modules\Identity\Application\Services\SendPasswordResetLinkService;
use Illuminate\Contracts\View\View;
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
        $this->status = $service->execute($this->form->email);
        $this->form->reset();
    }

    public function render(): View
    {
        return view('livewire.auth.forgot-password');
    }
}
