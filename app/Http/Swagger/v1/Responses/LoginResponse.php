<?php

namespace App\Http\Swagger\Responses;

/**
 * Class LoginResponse
 * @OA\Schema(
 *     description="Login response",
 *     title="Login response"
 * )
 */
class LoginResponse {

    /**
     * @OA\Property(
     *     description="Access token",
     *     title="access_token",
     * )
     *
     * @var string
     */
    private $access_token;

    /**
     * @OA\Property(
     *     description="Tipo de token",
     *     title="token_type",
     * )
     *
     * @var string
     */
    private $token_type;

    /**
     * @OA\Property(
     *     description="Fecha de expiración",
     *     title="expires_at",
     * )
     *
     * @var string
     */
    private $expires_at;
}