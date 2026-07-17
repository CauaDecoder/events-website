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
        $tenantId = $request->header('X-Tenant-ID') ?? $request->session()->get('tenant_id');

        if (! is_string($tenantId) && ! is_int($tenantId)) {
            throw new TenantNotResolvedException;
        }

        $this->context->set($tenantId);

        try {
            return $next($request);
        } finally {
            $this->context->clear();
        }
    }
}
