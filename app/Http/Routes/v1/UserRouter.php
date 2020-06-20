<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group(['prefix' => 'users'], function() {
    Route::group(['middleware' => 'auth:api'], function() {

        /**
        * @OA\Get(
        *   path="/users",
        *   summary="Lista de User",
        *   description="Devuelve una lista de User",
        *   tags={"User"},
        *   security={{"passport": {"*"}}},
        *   @OA\Parameter(
        *          name="paginable",
        *          description="Define si el resultado estará paginado",
        *          required=false,
        *          in="query",
        *          @OA\Schema(
        *              type="boolean"
        *          )
        *      ),
        *   @OA\Parameter(
        *          name="per_page",
        *          description="Define la cantidad de resultados por página",
        *          required=false,
        *          in="query",
        *          @OA\Schema(
        *              type="number"
        *          )
        *      ), 
        *   @OA\Parameter(
        *          name="relations",
        *          description="Define la un arreglo de las relaciones a obtener",
        *          required=false,
        *          style="deepObject",
        *          in="query",
        *          @OA\Schema(
        *              type="array",
        *              @OA\Items(type="string")
        *          )
        *      ),
        *   @OA\Response(
        *       response=200,
        *     @OA\JsonContent(
        *         type="array",
        *         @OA\Items(ref="#/components/schemas/User")
        *     ),
        *       description="Muestra los User."
        *     ),
        *     @OA\Response(
        *         response="400",
        *         description="Ha ocurrido un error."
        *     )
        * )
        */
        Route::get('', 'API\v1\UserController@index')->name("users.index");

        /**
        * @OA\Get(
        *   path="/users/{id}",
        *   summary="Obtener un User",
        *   description="Obtener un User por su ID",
        *   tags={"User"},
        *   security={{"passport": {"*"}}},
        *      @OA\Parameter(
        *          name="id",
        *          description="Identificador del User",
        *          required=true,
        *          in="path",
        *          @OA\Schema(
        *              type="integer"
        *          )
        *      ),
        *   @OA\Parameter(
        *          name="relations",
        *          description="Define un arreglo de las relaciones a obtener",
        *          required=false,
        *          style="deepObject",
        *          in="query",
        *          @OA\Schema(
        *              type="array",
        *              @OA\Items(type="string")
        *          )
        *      ),
        *   @OA\Response(
        *       response=200,
        *       @OA\MediaType(
        *           mediaType="application/json",
        *           @OA\Schema(ref="#/components/schemas/User")),
        *       description="Muestra la respuesta."
        *     ),
        *   @OA\Response(
        *       response="400",
        *       description="Ha ocurrido un error.",
        *        @OA\JsonContent(ref="#/components/schemas/Error")
        *     ),
        *   @OA\Response(
        *       response="404",
        *       description="El registro no se encuentra en la base de datos",
        *       @OA\JsonContent(ref="#/components/schemas/Error")
        *     )
        * )
        */
        Route::get('/{id}', 'API\v1\UserController@detail')->name('users.detail');

        /**
        * @OA\Post(
        *   path="/users",
        *   summary="Crear un User",
        *   description="Devuelve el User creado",
        *   tags={"User"},
        *   security={{"passport": {"*"}}},
        *   @OA\RequestBody(
        *       request="User",
        *       description="Objeto del User",
        *       required=true,
        *     @OA\JsonContent(
        *           ref="#/components/schemas/User",
        *     ),
        *   ),
        *   @OA\Response(
        *       response=200,
        *       @OA\MediaType(
        *           mediaType="application/json",
        *           @OA\Schema(ref="#/components/schemas/User")),
        *       description="Devuelve el User creado."
        *     ),
        *   @OA\Response(
        *       response="422",
        *       description="Falta algún parámetro."
        *     ),
        *   @OA\Response(
        *       response="400",
        *       description="Ha ocurrido un error."
        *     ),
        * )
        */
        Route::post('', 'API\v1\UserController@store')->name("users.store");

        /**
        * @OA\Patch(
        *   path="/users/{id}",
        *   summary="Actualizar un User",
        *   description="Permite editar un User mediante su ID",
        *   tags={"User"},
        *   security={{"passport": {"*"}}},
        *   @OA\RequestBody(
        *       request="User",
        *       description="Objeto del User",
        *       required=true,
        *     @OA\JsonContent(
        *           ref="#/components/schemas/User",
        *     ),
        *   ),
        *   @OA\Response(
        *       response=200,
        *       @OA\MediaType(
        *           mediaType="application/json",
        *           @OA\Schema(ref="#/components/schemas/User")),
        *       description="Devuelve el User actualizado."
        *     ),
        *   @OA\Response(
        *       response="422",
        *       description="Falta algún parámetro."
        *     ),
        *   @OA\Response(
        *       response="400",
        *       description="Ha ocurrido un error."
        *     ),
        * )
        */
        Route::patch('/{id}', 'API\v1\UserController@update')->name("users.update");

        /**
        * @OA\Delete(
        *   path="/users/{id}",
        *   summary="Elimina un User",
        *   description="Elimina un User por su ID",
        *   tags={"User"},
        *   security={{"passport": {"*"}}},
        *      @OA\Parameter(
        *          name="id",
        *          description="Identificador del User",
        *          required=true,
        *          in="path",
        *          @OA\Schema(
        *              type="integer"
        *          )
        *      ),
        *   @OA\Response(
        *       response=200,
        *       description="Devuelve un código 200."
        *     ),
        *   @OA\Response(
        *       response="400",
        *       description="Ha ocurrido un error.",
        *        @OA\JsonContent(ref="#/components/schemas/Error")
        *     ),
        *   @OA\Response(
        *       response="404",
        *       description="El registro no se encuentra en la base de datos",
        *       @OA\JsonContent(ref="#/components/schemas/Error")
        *     )
        * )
        */
        Route::delete('/{id}', 'API\v1\UserController@destroy')->name("users.destroy");
    });

    Route::group(['prefix'=> 'auth'], function() {

        /**
        * @OA\Post(
        *   path="/users/auth/login",
        *   summary="Inicio de sesión",
        *   description="Requiere el email del usuario y su contraseña para iniciar sesión. Correo debe estar verificado.",
        *   tags={"Auth"},
        *   security={},
        *   @OA\RequestBody(
        *       request="Login",
        *       description="Objeto del inicio de sesión",
        *       required=true,
        *     @OA\JsonContent(ref="#/components/schemas/LoginRequest")
        *   ),
        *   @OA\Response(
        *       response="200",
        *       description="El inicio de sesión fue exitoso",
        *       @OA\JsonContent(ref="#/components/schemas/LoginResponse")
        *     ),
        *   @OA\Response(
        *       response="400",
        *       description="Ha ocurrido un error.",
        *        @OA\JsonContent(ref="#/components/schemas/Error")
        *     ),
        *   @OA\Response(
        *       response="422",
        *       description="Algún campo no es correcto.",
        *        @OA\JsonContent(ref="#/components/schemas/Error")
        *     ),
        * )
        */
        Route::post('login', 'API\v1\UserController@login');
    });
});