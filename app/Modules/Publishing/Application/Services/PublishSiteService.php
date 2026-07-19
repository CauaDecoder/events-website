<?php

declare(strict_types=1);

namespace App\Modules\Publishing\Application\Services;

use App\Exceptions\BusinessRuleException;
use App\Models\User;
use App\Modules\Publishing\Infrastructure\Models\SitePublication;
use App\Modules\Sites\Infrastructure\Models\Site;
use App\Support\Tenancy\TenantContext;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

final class PublishSiteService
{
    public function execute(User $user, Site $site): SitePublication
    {
        abort_unless($site->event->tenant_id === app(TenantContext::class)->idFor($user), 403);
        $site->load(['event', 'pages']);

        if ($site->pages->isEmpty()) {
            throw new BusinessRuleException('O site precisa ter ao menos uma página antes de ser publicado.');
        }

        $payload = $this->payload($site);
        $encoded = json_encode($payload, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        $publication = DB::transaction(function () use ($user, $site, $payload, $encoded): SitePublication {
            $version = ((int) SitePublication::query()->where('site_id', $site->id)->lockForUpdate()->max('version')) + 1;

            $publication = SitePublication::query()->create([
                'site_id' => $site->id,
                'published_by' => $user->id,
                'version' => $version,
                'payload' => $payload,
                'checksum' => hash('sha256', $encoded),
                'published_at' => now(),
            ]);

            $site->update(['status' => 'published', 'published_at' => now()]);

            return $publication;
        });

        Cache::forget("public-invitation:{$site->slug}:latest");
        $site->domains()->each(fn ($domain) => Cache::forget("public-domain-invitation:{$domain->hostname}:latest"));

        return $publication;
    }

    private function payload(Site $site): array
    {
        return [
            'schema_version' => 1,
            'event' => [
                'id' => $site->event->id,
                'name' => $site->event->name,
                'type' => $site->event->event_type,
                'date' => $site->event->event_date?->toISOString(),
            ],
            'site' => [
                'id' => $site->id,
                'name' => $site->name,
                'slug' => $site->slug,
                'theme' => $site->theme ?? [],
                'settings' => $site->settings ?? [],
            ],
            'pages' => $site->pages->map(fn ($page): array => [
                'id' => $page->id,
                'name' => $page->name,
                'slug' => $page->slug,
                'is_home' => $page->is_home,
                'zones' => [
                    'header' => $page->header ?? [],
                    'content' => $page->content ?? [],
                    'footer' => $page->footer ?? [],
                ],
            ])->values()->all(),
        ];
    }
}
