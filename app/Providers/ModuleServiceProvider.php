<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

final class ModuleServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom([
            database_path('migrations/events'),
            database_path('migrations/sites'),
            database_path('migrations/billing'),
            database_path('migrations/publishing'),
            database_path('migrations/media'),
            database_path('migrations/tenancy'),
        ]);
    }
}
