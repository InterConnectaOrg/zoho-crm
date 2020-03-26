<?php

namespace Zoho\CRM\Facades;

use Illuminate\Http\JsonResponse;

/**
 * @author Gustavo L. <emw3wme@gmail.com>
 *
 * @since 1.0 First time this was introduced
 */
class APIResponse
{
    /**
     * Return json response.
     *
     * @param array  $data
     * @param string $message
     * @param string $code
     *
     * @return Illuminate\Http\JsonResponse
     */
    public static function make(bool $success = true, array $data = null, string $message = null, string $code = null, int $http_code = 200): JsonResponse
    {
        return response()->json([
            'success' => $success,
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ], $http_code);
    }

    /**
     * Return success json response.
     *
     * @since 1.0.0
     *
     * @param array  $data
     * @param string $message
     * @param string $code
     *
     * @return Illuminate\Http\JsonResponse
     */
    public static function success(array $data = null, string $message = null, string $code = null, int $http_code = 200): JsonResponse
    {
        return self::make(true, $data, $message, $code, $http_code);
    }

    /**
     * Return fail json response.
     *
     * @since 1.0.0
     *
     * @param string $message
     * @param string $code
     * @param array  $data
     *
     * @return Illuminate\Http\JsonResponse
     */
    public static function fail(string $message = null, string $code = null, array $data = null, int $http_code = 404): JsonResponse
    {
        return self::make(false, $data, $message, $code, $http_code);
    }
}
