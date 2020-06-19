<?php

namespace App\Http\Swagger\Requests;

/**
 * Class LoginRequest
 * @OA\Schema(
 *     description="Login Request",
 *     title="Login Request"
 * )
 */
class LoginRequest
{
    /**       @OA\Property(
    *           property="email",
    *           type="string",
    *           description="Correo electrónico del usuario",
    *           example="johndoe@example.com"
    *       )
    */
    private $email;

    /**
    *       @OA\Property(
    *           property="password",
    *           type="string",
    *           description="Contraseña del usuario (Oculto)"
    *        )
    */
    private $password;

    /**
    *       @OA\Property(
    *           property="remember_me",
    *           type="boolean",
    *           description="Determina si el token durará más tiempo",
    *           example=false
    *         )
    */
    private $remember_me;
}