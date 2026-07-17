<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use App\Http\Resources\Api\V1\Users\UserResource;
use App\Http\Responses\ApiResponse;
use App\Modules\Identity\Application\DTOs\RegisterUserData;
use App\Modules\Identity\Application\Services\RegisterUserService;
use Illuminate\Http\JsonResponse;

final class RegisterController extends Controller
{
    public function __invoke(RegisterRequest $request, RegisterUserService $service): JsonResponse
    {
        $user = $service->execute(new RegisterUserData(
            name: $request->string('name')->toString(),
            email: $request->string('email')->toString(),
            password: $request->string('password')->toString(),
        ));

        return ApiResponse::success([
            'user' => new UserResource($user),
            'token' => $user->createToken($request->string('device_name')->toString())->plainTextToken,
        ], 201);
    }
}
