<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\Services;

use App\Models\User;

final class UpdateProfileService
{
    public function execute(User $user, string $name, string $email): bool
    {
        $emailChanged = $user->email !== $email;

        $user->forceFill([
            'name' => $name,
            'email' => $email,
            'email_verified_at' => $emailChanged ? null : $user->email_verified_at,
        ])->save();

        if ($emailChanged) {
            $user->sendEmailVerificationNotification();
        }

        return $emailChanged;
    }
}
