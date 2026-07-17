<?php

declare(strict_types=1);

namespace App\Livewire\Auth;

use App\Livewire\Forms\Users\ConfirmPasswordForm;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.auth')]
#[Title('Confirmar senha')]
final class ConfirmPassword extends Component
{
    public ConfirmPasswordForm $form;

    public function confirm(): void
    {
        $this->form->validate();

        if (! Hash::check($this->form->password, auth()->user()->password)) {
            throw ValidationException::withMessages(['form.password' => __('auth.password')]);
        }

        session()->passwordConfirmed();
        $this->redirectIntended(default: route('client.dashboard'), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.auth.confirm-password');
    }
}
