<?php

namespace App\Data\Repository\v1;

use App\Core\Data\BaseRepository as BaseRepository;
use App\User;
use Illuminate\Database\Eloquent\Model;

/** 
* @brief Clase para almacenar y representar User.
* @author 
* @date 
*/
class UserRepository extends BaseRepository {

    /**
    * @brief Función para recuperación modelo
    *
    * Función para poder recuperar el modelo en cuestion.
    */
    function getModel(): Model
    {
        $model = User::class;

        return new $model;
    }

    /**
    * @brief Función para recuperación del nombre del modelo
    *
    * Función para poder recuperar el nombre del modelo en cuestion en una cadena de texto.
    */
    function getTransFileName(): string
    {
        return 'User';
    }
}