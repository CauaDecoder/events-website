<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Exceptions\TenantNotResolvedException;
use App\Support\Tenancy\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final readonly class ResolveTenant
{
    public function __construct(private TenantContext $context) {}

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user() ?? throw new TenantNotResolvedException;
        $tenant = $this->context->resolveFor($user, $request->header('X-Tenant-ID') ?? $request->session()->get('tenant_id'));
        $request->session()->put('tenant_id', $tenant->id);
        setPermissionsTeamId($tenant->id);
        $user->unsetRelation('roles')->unsetRelation('permissions');

        try {
            return $next($request);
        } finally {
            $this->context->clear();
        }
    }
}
