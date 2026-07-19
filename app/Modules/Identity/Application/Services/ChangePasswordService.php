<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

final class ChangePasswordService
{
    public function execute(User $user, string $password): void
    {
        $user->forceFill(['password' => Hash::make($password)])->save();
    }
}
