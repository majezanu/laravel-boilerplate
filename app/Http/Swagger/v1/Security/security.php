<?php

/**
 * @license Apache 2.0
 */

/**
 * @OA\SecurityScheme(
 *     type="oauth2",
 *     name="external_auth",
 *     securityScheme="external_auth",
 *     @OA\Flow(
 *         flow="implicit",
 *         authorizationUrl="http://example.swagger.io/oauth/dialog",
 *         scopes={
 *             "write:models": "modify models in your account",
 *             "read:models": "read your models",
 *         }
 *     )
 * )
 * @OA\SecurityScheme(
 *     type="apiKey",
 *     in="header",
 *     securityScheme="simple-api-key",
 *     name="simple-api-key"
 * )
 * @OA\SecurityScheme(
 *     securityScheme="Bearer",
 *     type="apiKey",
 *     name="bearer_auth",
 *     in="header"
 * )
 */
