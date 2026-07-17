<?php

declare(strict_types=1);

namespace App\Livewire\Forms\Users;

use App\Modules\Identity\Application\DTOs\CredentialsData;
use Livewire\Attributes\Validate;
use Livewire\Form;

final class LoginForm extends Form
{
    #[Validate('required|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    public function toDto(): CredentialsData
    {
        return new CredentialsData($this->email, $this->password);
    }
}
