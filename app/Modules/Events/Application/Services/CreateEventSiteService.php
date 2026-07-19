<?php

declare(strict_types=1);

namespace App\Modules\Events\Application\Services;

use App\Models\User;
use App\Modules\Events\Application\DTOs\CreateEventData;
use App\Modules\Events\Infrastructure\Models\Event;
use App\Modules\Sites\Infrastructure\Models\Site;
use App\Modules\Sites\Infrastructure\Models\SitePage;
use App\Support\Tenancy\TenantContext;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class CreateEventSiteService
{
    public function execute(User $user, CreateEventData $data): SitePage
    {
        return DB::transaction(function () use ($user, $data): SitePage {
            $base = Str::slug($data->name) ?: 'evento';
            $suffix = Str::lower(Str::random(6));
            $event = Event::query()->create(['tenant_id' => app(TenantContext::class)->idFor($user), 'user_id' => $user->id, 'name' => $data->name, 'slug' => "{$base}-{$suffix}", 'event_type' => $data->eventType, 'event_date' => $data->eventDate]);
            $site = Site::query()->create(['event_id' => $event->id, 'name' => $data->name, 'slug' => "{$base}-{$suffix}", 'theme' => ['primary' => '#E58775', 'font' => 'Instrument Sans'], 'settings' => []]);

            return SitePage::query()->create(['site_id' => $site->id, 'name' => 'Início', 'slug' => 'inicio', 'is_home' => true, 'header' => [], 'content' => [], 'footer' => []]);
        });
    }
}
