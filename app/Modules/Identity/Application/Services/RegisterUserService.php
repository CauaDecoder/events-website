<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\Services;

use App\Models\User;
use App\Modules\Identity\Application\DTOs\RegisterUserData;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;

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

            event(new Registered($user));

            return $user;
        });
    }
}
