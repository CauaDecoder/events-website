<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\Queries;

use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;

final class AdminDashboardMetricsQuery
{
    public function execute(): array
    {
        return [
            'users' => User::query()->count(),
            'verified_users' => User::query()->whereNotNull('email_verified_at')->count(),
            'new_users_this_month' => User::query()->where('created_at', '>=', now()->startOfMonth())->count(),
            'active_api_tokens' => PersonalAccessToken::query()->count(),
        ];
    }
}
