<?php

namespace Package\Auth\Helpers;

use Illuminate\Http\JsonResponse;

class ResponseBuilder
{
    /**
     * Create a successful response.
     *
     * @param mixed $data
     * @param string|null $message
     * @param int $statusCode
     * @return JsonResponse
     */
    public static function Ok($data = null, ?string $message = null, int $statusCode = 200): JsonResponse
    {
        $response = [
            'success' => true,
            'data' => $data,
        ];

        if ($message) {
            $response['message'] = $message;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Create an error response.
     *
     * @param string $message
     * @param string|null $error
     * @param int $statusCode
     * @return JsonResponse
     */
    public static function Error(string $message, ?string $error = null, int $statusCode = 400): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($error) {
            $response['error'] = $error;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Create a validation error response.
     *
     * @param array $errors
     * @param string $message
     * @return JsonResponse
     */
    public static function ValidationError(array $errors, string $message = 'Validation failed'): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], 422);
    }

    /**
     * Create an unauthorized response.
     *
     * @param string $message
     * @return JsonResponse
     */
    public static function Unauthorized(string $message = 'Unauthorized'): JsonResponse
    {
        return self::Error($message, null, 401);
    }

    /**
     * Create a forbidden response.
     *
     * @param string $message
     * @return JsonResponse
     */
    public static function Forbidden(string $message = 'Forbidden'): JsonResponse
    {
        return self::Error($message, null, 403);
    }

    /**
     * Create a not found response.
     *
     * @param string $message
     * @return JsonResponse
     */
    public static function NotFound(string $message = 'Not found'): JsonResponse
    {
        return self::Error($message, null, 404);
    }

    /**
     * Create a server error response.
     *
     * @param string $message
     * @param string|null $error
     * @return JsonResponse
     */
    public static function ServerError(string $message = 'Server error', ?string $error = null): JsonResponse
    {
        return self::Error($message, $error, 500);
    }
} 