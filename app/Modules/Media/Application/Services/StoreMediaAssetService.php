<?php

declare(strict_types=1);

namespace App\Modules\Media\Application\Services;

use App\Models\User;
use App\Modules\Media\Infrastructure\Models\MediaAsset;
use App\Support\Tenancy\TenantContext;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

final class StoreMediaAssetService
{
    public function handle(User $user, TemporaryUploadedFile $file): MediaAsset
    {
        $path = $file->store("media/{$user->getKey()}", 'public');

        return MediaAsset::query()->create([
            'tenant_id' => app(TenantContext::class)->idFor($user),
            'user_id' => $user->getKey(),
            'disk' => 'public',
            'path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType() ?: 'application/octet-stream',
            'size' => $file->getSize(),
        ]);
    }
}
