<?php

declare(strict_types=1);

namespace App\Livewire\Forms\Settings;

use Illuminate\Validation\Rules\Password;
use Livewire\Form;

final class PasswordForm extends Form
{
    public string $current_password = '';

    public string $password = '';

    public string $password_confirmation = '';

    public function rules(): array
    {
        return [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults(), 'different:current_password'],
        ];
    }
}
