<?php

declare(strict_types=1);

namespace App\Modules\Tenancy\Infrastructure\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class TeamMember extends Model
{
    protected $fillable = ['tenant_id', 'owner_user_id', 'user_id', 'email', 'role', 'status', 'invitation_token', 'accepted_at'];

    protected function casts(): array
    {
        return ['accepted_at' => 'datetime'];
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
