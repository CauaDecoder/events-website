<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\ResetPasswordRequest;
use App\Http\Responses\ApiResponse;
use App\Modules\Identity\Application\DTOs\ResetPasswordData;
use App\Modules\Identity\Application\Services\ResetPasswordService;
use Illuminate\Http\JsonResponse;

final class ResetPasswordController extends Controller
{
    public function __invoke(ResetPasswordRequest $request, ResetPasswordService $service): JsonResponse
    {
        $message = $service->execute(new ResetPasswordData(
            email: $request->string('email')->toString(),
            password: $request->string('password')->toString(),
            token: $request->string('token')->toString(),
        ));

        return ApiResponse::success(['message' => $message]);
    }
}
