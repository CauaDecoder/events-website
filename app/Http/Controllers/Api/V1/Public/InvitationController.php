<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Public;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Modules\Publishing\Infrastructure\Models\SitePublication;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

final class InvitationController extends Controller
{
    public function __invoke(string $slug): JsonResponse
    {
        $publication = Cache::remember(
            "public-invitation:{$slug}:latest",
            now()->addHour(),
            fn () => SitePublication::query()
                ->whereHas('site', fn ($query) => $query->where('slug', $slug)->where('status', 'published'))
                ->latest('version')
                ->first(),
        );

        if (! $publication) {
            return ApiResponse::error('INVITATION_NOT_FOUND', 'Convite não encontrado ou ainda não publicado.', 404);
        }

        return ApiResponse::success($publication->payload, meta: [
            'publication' => [
                'version' => $publication->version,
                'checksum' => $publication->checksum,
                'published_at' => $publication->published_at->toISOString(),
            ],
        ])->withHeaders([
            'ETag' => '"'.$publication->checksum.'"',
            'Cache-Control' => 'public, max-age=60, s-maxage=300, stale-while-revalidate=86400',
        ]);
    }
}
