<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\Services;

use App\Models\User;
use App\Modules\Identity\Application\DTOs\RegisterUserData;
use App\Modules\Tenancy\Infrastructure\Models\Tenant;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class RegisterUserService
{
    public function execute(RegisterUserData $data): User
    {
        return DB::transaction(function () use ($data): User {
            $user = User::query()->create([
                'name' => $data->name,
                'email' => mb_strtolower($data->email),
                'password' => $data->password,
            ]);

            Tenant::query()->create([
                'owner_user_id' => $user->id,
                'name' => "Workspace de {$user->name}",
                'slug' => 'workspace-'.$user->id.'-'.Str::lower(Str::random(5)),
            ]);

            event(new Registered($user));

            return $user;
        });
    }
}
