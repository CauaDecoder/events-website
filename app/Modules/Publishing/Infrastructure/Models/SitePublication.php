<?php

declare(strict_types=1);

namespace App\Modules\Publishing\Infrastructure\Models;

use App\Models\User;
use App\Modules\Sites\Infrastructure\Models\Site;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class SitePublication extends Model
{
    protected $fillable = ['site_id', 'published_by', 'version', 'payload', 'checksum', 'published_at'];

    protected function casts(): array
    {
        return ['payload' => 'array', 'published_at' => 'datetime'];
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'published_by');
    }
}
