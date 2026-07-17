<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class SetPermissionTeam
{
    public function handle(Request $request, Closure $next): Response
    {
        setPermissionsTeamId((int) $request->session()->get('tenant_id', 0));
        $request->user()?->unsetRelation('roles')->unsetRelation('permissions');

        return $next($request);
    }
}
