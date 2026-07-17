<?php

declare(strict_types=1);

namespace App\Support\Tenancy;

use App\Exceptions\TenantNotResolvedException;

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
}
