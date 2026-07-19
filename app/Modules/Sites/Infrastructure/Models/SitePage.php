<?php

declare(strict_types=1);

namespace App\Modules\Sites\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class SitePage extends Model
{
    protected $fillable = ['site_id', 'name', 'slug', 'is_home', 'sort_order', 'header', 'content', 'footer'];

    protected function casts(): array
    {
        return ['is_home' => 'boolean', 'header' => 'array', 'content' => 'array', 'footer' => 'array'];
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }
}
