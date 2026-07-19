<?php

declare(strict_types=1);

namespace App\Modules\Domains\Application\Services;

use App\Modules\Domains\Infrastructure\Models\Domain;
use App\Modules\Sites\Infrastructure\Models\Site;
use App\Modules\Tenancy\Infrastructure\Models\Tenant;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

final class CreateDomainService
{
    public function execute(Tenant $tenant, Site $site, string $hostname): Domain
    {
        abort_unless($site->event->tenant_id === $tenant->id, 403);
        $hostname = Str::lower(trim(preg_replace('#^https?://#i', '', $hostname), " /\t\n\r\0\x0B"));

        if (! filter_var($hostname, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME) || ! str_contains($hostname, '.')) {
            throw ValidationException::withMessages(['hostname' => 'Informe um domínio válido, como convite.seudominio.com.']);
        }

        return Domain::query()->create([
            'tenant_id' => $tenant->id,
            'site_id' => $site->id,
            'hostname' => $hostname,
            'verification_token' => Str::random(48),
        ]);
    }
}
