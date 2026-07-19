<?php

declare(strict_types=1);

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Modules\Tenancy\Infrastructure\Models\Tenant;
use App\Support\Tenancy\TenantContext;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class SwitchTenantController extends Controller
{
    public function __invoke(Request $request, Tenant $tenant, TenantContext $context): RedirectResponse
    {
        $resolved = $context->resolveFor($request->user(), $tenant->id);
        abort_unless($resolved->is($tenant), 403);
        $request->session()->put('tenant_id', $tenant->id);
        setPermissionsTeamId($tenant->id);

        return to_route('client.dashboard');
    }
}
