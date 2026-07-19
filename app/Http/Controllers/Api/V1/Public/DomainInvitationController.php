<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Public;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Modules\Domains\Infrastructure\Models\Domain;
use App\Modules\Publishing\Infrastructure\Models\SitePublication;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

final class DomainInvitationController extends Controller
{
    public function __invoke(string $hostname): JsonResponse
    {
        $hostname = Str::lower($hostname);
        $publication = Cache::remember("public-domain-invitation:{$hostname}:latest", now()->addMinutes(10), function () use ($hostname) {
            $domain = Domain::query()->where('hostname', $hostname)->where('status', 'verified')->first();

            return $domain?->site_id
                ? SitePublication::query()->where('site_id', $domain->site_id)->whereHas('site', fn ($site) => $site->where('status', 'published'))->latest('version')->first()
                : null;
        });

        if (! $publication) {
            return ApiResponse::error('DOMAIN_INVITATION_NOT_FOUND', 'Nenhum convite publicado foi encontrado para este domínio.', 404);
        }

        return ApiResponse::success($publication->payload, meta: ['publication' => ['version' => $publication->version, 'checksum' => $publication->checksum, 'published_at' => $publication->published_at->toISOString()]])
            ->withHeaders(['ETag' => '"'.$publication->checksum.'"', 'Cache-Control' => 'public, max-age=60, s-maxage=300']);
    }
}
