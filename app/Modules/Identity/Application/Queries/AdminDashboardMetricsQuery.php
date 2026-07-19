<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\Queries;

use App\Models\User;
use App\Modules\Events\Infrastructure\Models\Event;
use App\Modules\Media\Infrastructure\Models\MediaAsset;
use App\Modules\Sites\Infrastructure\Models\Site;
use Laravel\Sanctum\PersonalAccessToken;

final class AdminDashboardMetricsQuery
{
    public function execute(): array
    {
        $users = User::query()->count();
        $premium = User::query()->where('plan', 'premium')->count();

        return [
            'users' => $users,
            'premium_users' => $premium,
            'free_users' => $users - $premium,
            'conversion_rate' => $users > 0 ? round(($premium / $users) * 100, 1) : 0,
            'estimated_mrr' => $premium * (float) config('plans.premium.monthly_price'),
            'verified_users' => User::query()->whereNotNull('email_verified_at')->count(),
            'new_users_this_month' => User::query()->where('created_at', '>=', now()->startOfMonth())->count(),
            'active_api_tokens' => PersonalAccessToken::query()->count(),
            'events' => Event::query()->count(),
            'published_sites' => Site::query()->where('status', 'published')->count(),
            'media_assets' => MediaAsset::query()->count(),
        ];
    }
}
