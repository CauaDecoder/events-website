<?php

declare(strict_types=1);

namespace App\Livewire\Forms\Settings;

use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Form;

final class ProfileForm extends Form
{
    public string $name = '';

    public string $email = '';

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique(User::class, 'email')->ignore(auth()->id())],
        ];
    }
}
