<?php

declare(strict_types=1);

namespace App\Support\Tenancy;

use App\Exceptions\TenantNotResolvedException;
use App\Models\User;
use App\Modules\Tenancy\Infrastructure\Models\Tenant;
use Illuminate\Support\Str;

final class TenantContext
{
    private ?string $tenantId = null;

    public function set(string|int $tenantId): void
    {
        $this->tenantId = (string) $tenantId;
    }

    public function id(): string
    {
        return $this->tenantId ?? throw new TenantNotResolvedException;
    }

    public function idOrNull(): ?string
    {
        return $this->tenantId;
    }

    public function resolved(): bool
    {
        return $this->tenantId !== null;
    }

    public function clear(): void
    {
        $this->tenantId = null;
    }

    public function resolveFor(User $user, string|int|null $requestedId = null): Tenant
    {
        $query = Tenant::query()->where(fn ($query) => $query
            ->where('owner_user_id', $user->id)
            ->orWhereHas('members', fn ($members) => $members->where('user_id', $user->id)->where('status', 'active')));

        $tenant = $requestedId ? (clone $query)->find($requestedId) : null;
        $tenant ??= $query->first();
        $tenant ??= Tenant::query()->create([
            'owner_user_id' => $user->id,
            'name' => "Workspace de {$user->name}",
            'slug' => 'workspace-'.$user->id.'-'.Str::lower(Str::random(5)),
        ]);

        $this->set($tenant->id);

        return $tenant;
    }

    public function idFor(User $user): int
    {
        if ($this->resolved()) {
            $tenantId = (int) $this->id();
            $allowed = Tenant::query()->whereKey($tenantId)->where(fn ($query) => $query
                ->where('owner_user_id', $user->id)
                ->orWhereHas('members', fn ($members) => $members->where('user_id', $user->id)->where('status', 'active')))->exists();

            if ($allowed) {
                return $tenantId;
            }

            $this->clear();
        }

        return (int) $this->resolveFor($user, session('tenant_id'))->id;
    }
}
