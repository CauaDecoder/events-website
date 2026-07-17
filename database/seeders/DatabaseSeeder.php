<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);

        $admin = User::query()->firstOrCreate(
            ['email' => 'admin@events.local'],
            ['name' => 'Administrador', 'password' => 'Admin@123', 'email_verified_at' => now()],
        );
        $admin->forceFill(['email_verified_at' => $admin->email_verified_at ?? now()])->save();
        setPermissionsTeamId(0);
        $admin->assignRole(Role::query()->where('name', 'super-admin')->where('team_id', 0)->firstOrFail());

        $client = User::query()->firstOrCreate(
            ['email' => 'teste@events.local'],
            ['name' => 'Usuário de Teste', 'password' => 'Teste@123', 'email_verified_at' => now()],
        );
        $client->forceFill(['email_verified_at' => $client->email_verified_at ?? now()])->save();
    }
}
