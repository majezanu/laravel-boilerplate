<?php

namespace App\Http\Controllers\API\v1;

use App\Core\Http\BaseController as BaseController;

use App\Domain\Service\v1\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/** 
* @brief Clase para controlar las peticiones de servicio web CRUD
* @author 
* @date 
*/
class UserController extends  BaseController{

    public function __construct(UserService $service) {
        parent::__construct($service);
    }

    /**
     * login api
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => ['required', 'email' ],
            'password' => ['required'],
            'remember_me' => ['boolean']
        ]);
        $response = $this->service->login($request->email, $request->password, $request->remember_me);
        return response()->json($response['response'], $response['code']);

    }
}