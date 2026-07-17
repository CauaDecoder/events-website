<?php

declare(strict_types=1);

namespace App\Livewire\Forms\Users;

use Livewire\Attributes\Validate;
use Livewire\Form;

final class ConfirmPasswordForm extends Form
{
    #[Validate('required|string')]
    public string $password = '';
}
