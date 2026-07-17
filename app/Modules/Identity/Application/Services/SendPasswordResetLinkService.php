<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\Services;

use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

final class SendPasswordResetLinkService
{
    public function execute(string $email): string
    {
        $status = Password::sendResetLink(['email' => mb_strtolower($email)]);

        if ($status !== Password::RESET_LINK_SENT) {
            throw ValidationException::withMessages(['email' => __($status)]);
        }

        return __($status);
    }
}
