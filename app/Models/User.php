<?php

namespace App\Models;

use App\Modules\Events\Infrastructure\Models\Event;
use App\Modules\Media\Infrastructure\Models\MediaAsset;
use App\Modules\Tenancy\Infrastructure\Models\TeamMember;
use App\Modules\Tenancy\Infrastructure\Models\Tenant;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmailContract
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, HasRoles, Notifiable;

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function mediaAssets(): HasMany
    {
        return $this->hasMany(MediaAsset::class);
    }

    public function teamMembers(): HasMany
    {
        return $this->hasMany(TeamMember::class, 'owner_user_id');
    }

    public function ownedTenants(): HasMany
    {
        return $this->hasMany(Tenant::class, 'owner_user_id');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
