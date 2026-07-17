<?php

declare(strict_types=1);

namespace App\Livewire\Forms\Users;

use App\Models\User;
use App\Modules\Identity\Application\DTOs\RegisterUserData;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\Form;

final class RegisterForm extends Form
{
    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique(User::class, 'email')],
            'password' => ['required', 'confirmed', Password::defaults()],
        ];
    }

    public function toDto(): RegisterUserData
    {
        return new RegisterUserData($this->name, $this->email, $this->password);
    }
}
