<?php

declare(strict_types=1);

namespace App\Modules\Tenancy\Infrastructure\Models;

use App\Models\User;
use App\Modules\Domains\Infrastructure\Models\Domain;
use App\Modules\Events\Infrastructure\Models\Event;
use App\Modules\Media\Infrastructure\Models\MediaAsset;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Tenant extends Model
{
    protected $fillable = ['owner_user_id', 'name', 'slug'];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function mediaAssets(): HasMany
    {
        return $this->hasMany(MediaAsset::class);
    }

    public function members(): HasMany
    {
        return $this->hasMany(TeamMember::class);
    }

    public function domains(): HasMany
    {
        return $this->hasMany(Domain::class);
    }
}
