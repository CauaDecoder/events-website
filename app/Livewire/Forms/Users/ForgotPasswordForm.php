<?php

declare(strict_types=1);

namespace App\Livewire\Forms\Users;

use Livewire\Attributes\Validate;
use Livewire\Form;

final class ForgotPasswordForm extends Form
{
    #[Validate('required|email')]
    public string $email = '';
}
