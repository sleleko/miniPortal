<?php

namespace App\Processors\Security;

use App\Processors\Processor;
use App\Model\User;
use Slim\Http\Response;

class Login extends Processor
{

    /**
     * @return Response
     */
    public function post()
    {
        $username = trim($this->getProperty('username'));
        $password = trim($this->getProperty('password'));

        /** @var User $user */
        if ($user = User::query()->where(['username' => $username])->first()) {
            if ($user->verifyPassword($password)) {
                return !$user->active
                    ? $this->failure('Specified user is not active')
                    : $this->success([
                        'token' => $this->container->makeToken($user->id),
                    ]);
            }
        }

        return $this->failure('Wrong username or password');
    }

}