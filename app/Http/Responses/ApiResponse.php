<?php

declare(strict_types=1);

namespace App\Http\Responses;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;

final class ApiResponse
{
    public static function success(mixed $data = null, int $status = 200, array $meta = []): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $data,
            'meta' => self::meta($meta),
        ], $status);
    }

    public static function paginated(LengthAwarePaginator $paginator): JsonResponse
    {
        return self::success($paginator->items(), meta: [
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
            ],
        ]);
    }

    public static function error(
        string $code,
        string $message,
        int $status,
        array $details = [],
    ): JsonResponse {
        return response()->json([
            'success' => false,
            'error' => [
                'code' => $code,
                'message' => $message,
                'details' => $details,
            ],
            'meta' => self::meta(),
        ], $status);
    }

    private static function meta(array $meta = []): array
    {
        $requestId = request()?->attributes->get('request_id');

        return array_filter([
            ...$meta,
            'request_id' => $requestId,
        ], static fn (mixed $value): bool => $value !== null);
    }
}
