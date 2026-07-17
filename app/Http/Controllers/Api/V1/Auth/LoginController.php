<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use App\Http\Resources\Api\V1\Users\UserResource;
use App\Http\Responses\ApiResponse;
use App\Modules\Identity\Application\DTOs\CredentialsData;
use App\Modules\Identity\Application\Services\AuthenticateUserService;
use Illuminate\Http\JsonResponse;

final class LoginController extends Controller
{
    public function __invoke(LoginRequest $request, AuthenticateUserService $service): JsonResponse
    {
        $user = $service->execute(new CredentialsData(
            email: $request->string('email')->toString(),
            password: $request->string('password')->toString(),
        ));

        return ApiResponse::success([
            'user' => new UserResource($user),
            'token' => $user->createToken($request->string('device_name')->toString())->plainTextToken,
        ]);
    }
}
