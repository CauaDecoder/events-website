<?php

declare(strict_types=1);

namespace App\Modules\Billing\Application\Services;

use App\Models\User;
use InvalidArgumentException;

final class ChangeUserPlanService
{
    public function execute(User $user, string $plan): void
    {
        if (! array_key_exists($plan, config('plans'))) {
            throw new InvalidArgumentException('Plano inválido.');
        }

        $user->forceFill(['plan' => $plan])->save();
    }
}
