<?php

declare(strict_types=1);

namespace App\Support\Types;

use App\Models\User;

final class FeatureManager
{
    public function allows(User $user, string $feature): bool
    {
        return in_array($feature, config("plans.{$user->plan}.features", []), true);
    }
}
