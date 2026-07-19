<?php

declare(strict_types=1);

namespace App\Modules\Media\Infrastructure\Models;

use App\Models\User;
use App\Support\Tenancy\TenantContext;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

final class MediaAsset extends Model
{
    protected $fillable = ['tenant_id', 'user_id', 'disk', 'path', 'original_name', 'mime_type', 'size', 'alt_text'];

    protected $appends = ['url'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getUrlAttribute(): string
    {
        return Storage::disk($this->disk)->url($this->path);
    }

    protected static function booted(): void
    {
        self::creating(function (MediaAsset $asset): void {
            if (! $asset->tenant_id && $asset->user_id) {
                $asset->tenant_id = app(TenantContext::class)->idFor(User::query()->findOrFail($asset->user_id));
            }
        });
    }
}
