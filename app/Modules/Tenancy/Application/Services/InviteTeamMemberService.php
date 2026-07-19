<?php

declare(strict_types=1);

namespace App\Modules\Tenancy\Application\Services;

use App\Models\User;
use App\Modules\Tenancy\Infrastructure\Models\TeamMember;
use App\Modules\Tenancy\Infrastructure\Models\Tenant;
use App\Support\Tenancy\TenantContext;
use Illuminate\Support\Str;

final class InviteTeamMemberService
{
    public function handle(User $owner, string $email, string $role): TeamMember
    {
        $user = User::query()->where('email', $email)->first();
        $tenantId = app(TenantContext::class)->idFor($owner);
        $tenantOwnerId = Tenant::query()->findOrFail($tenantId)->owner_user_id;

        return TeamMember::query()->create([
            'tenant_id' => $tenantId,
            'owner_user_id' => $tenantOwnerId,
            'user_id' => $user?->getKey(),
            'email' => $email,
            'role' => $role,
            'status' => $user ? 'active' : 'pending',
            'invitation_token' => Str::random(64),
            'accepted_at' => $user ? now() : null,
        ]);
    }
}
