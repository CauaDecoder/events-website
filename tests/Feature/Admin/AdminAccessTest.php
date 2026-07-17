<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

final class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_access_admin_panel(): void
    {
        $this->seed(RoleSeeder::class);
        setPermissionsTeamId(0);

        $admin = User::factory()->create();
        $admin->assignRole(Role::query()->where('name', 'super-admin')->where('team_id', 0)->firstOrFail());

        $this->actingAs($admin)->get('/admin')->assertOk();
    }

    public function test_regular_client_cannot_access_admin_panel(): void
    {
        $this->seed(RoleSeeder::class);

        $this->actingAs(User::factory()->create())->get('/admin')->assertForbidden();
    }
}
