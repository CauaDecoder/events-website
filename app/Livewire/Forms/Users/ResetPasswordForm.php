<?php

declare(strict_types=1);

namespace App\Livewire\Forms\Users;

use App\Modules\Identity\Application\DTOs\ResetPasswordData;
use Illuminate\Validation\Rules\Password;
use Livewire\Form;

final class ResetPasswordForm extends Form
{
    public string $email = '';

    public string $token = '';

    public string $password = '';

    public string $password_confirmation = '';

    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'token' => ['required', 'string'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ];
    }

    public function toDto(): ResetPasswordData
    {
        return new ResetPasswordData($this->email, $this->password, $this->token);
    }
}
