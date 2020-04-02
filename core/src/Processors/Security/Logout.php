<?php

namespace App\Processors\Security;

use App\Processors\Processor;
use App\Model\UserToken;
use Slim\Http\Response;

class Logout extends Processor
{

    protected $scope = 'profile';


    /**
     * @return Response
     */
    public function post()
    {
        /** @var UserToken $token */
        if ($token = $this->container->user->tokens()->where('token', '=', $this->container->request->getAttribute('token'))->first()) {
            $token->active = false;
            $token->save();
        }

        return $this->success();
    }

}