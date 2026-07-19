<?php

declare(strict_types=1);

namespace App\Modules\Media\Application\Services;

use App\Modules\Media\Infrastructure\Models\MediaAsset;
use Illuminate\Support\Facades\Storage;

final class DeleteMediaAssetService
{
    public function handle(MediaAsset $asset): void
    {
        Storage::disk($asset->disk)->delete($asset->path);
        $asset->delete();
    }
}
