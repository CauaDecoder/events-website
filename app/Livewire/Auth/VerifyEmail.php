<?php

declare(strict_types=1);

namespace App\Livewire\Auth;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.auth')]
#[Title('Verificar e-mail')]
final class VerifyEmail extends Component
{
    public bool $sent = false;

    public function resend(): void
    {
        if (auth()->user()->hasVerifiedEmail()) {
            $this->redirectRoute('client.dashboard', navigate: true);

            return;
        }

        auth()->user()->sendEmailVerificationNotification();
        $this->sent = true;
    }

    public function render(): View
    {
        return view('livewire.auth.verify-email');
    }
}
