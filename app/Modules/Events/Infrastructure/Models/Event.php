<?php

declare(strict_types=1);

namespace App\Modules\Events\Infrastructure\Models;

use App\Models\User;
use App\Modules\Sites\Infrastructure\Models\Site;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

final class Event extends Model
{
    protected $fillable = ['tenant_id', 'user_id', 'name', 'slug', 'event_type', 'event_date', 'status'];

    protected function casts(): array
    {
        return ['event_date' => 'datetime'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function site(): HasOne
    {
        return $this->hasOne(Site::class);
    }
}
