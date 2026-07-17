<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

final class RoleSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();
        setPermissionsTeamId(0);

        foreach (['super-admin', 'client-owner', 'client-member'] as $role) {
            Role::query()->firstOrCreate([
                'team_id' => 0,
                'name' => $role,
                'guard_name' => 'web',
            ]);
        }
    }
}
