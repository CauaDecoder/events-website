<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\ForgotPasswordRequest;
use App\Http\Responses\ApiResponse;
use App\Modules\Identity\Application\Services\SendPasswordResetLinkService;
use Illuminate\Http\JsonResponse;

final class ForgotPasswordController extends Controller
{
    public function __invoke(ForgotPasswordRequest $request, SendPasswordResetLinkService $service): JsonResponse
    {
        $message = $service->execute($request->string('email')->toString());

        return ApiResponse::success(['message' => $message]);
    }
}
