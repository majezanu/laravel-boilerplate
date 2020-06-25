<?php

namespace App\Domain\Service\v1;

use App\Core\Domain\BaseService as BaseService;
use App\Data\Repository\v1\UserRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

/** 
* @brief Clase para implementar las reglas de negocio de UserRepository
* @author 
* @date 
*/
class UserService extends BaseService {

    public function __construct(UserRepository $repository) {
        parent::__construct($repository);
    }

    public function login(string $email, string $password, bool $rememberMe)
    {
        try {
            if (Auth::attempt(['email' => $email, 'password' => $password])) {
                $user = Auth::user();
                if($user->hasVerifiedEmail()) {
                    $tokenResult = $user->createToken('Personal Access Token');
                    $response =  ['response' =>[
                        'email' => $user->email,
                        'token' => $tokenResult->accessToken,
                        'TokenType' => 'Bearer',
                        'expires_at' => Carbon::parse(
                            $tokenResult->token->expires_at
                        )->toDateTimeString()
                        ], 'code' => $this->successStatus];
                }
                else
                    $response = ['response' => ['error' => 'Usuario no confirmado'], 'code' =>$this->unauthorizedStatus];
            } else {
                $response = ['response' => ['error' => 'Unauthorized'], 'code' => $this->unauthorizedStatus];
            }
        }
        catch (\Exception $e) {
            $response = ['response' => ['error' => $e->getMessage()], 'code' => $this->errorStatus];
        }
        return $response;
    }
}