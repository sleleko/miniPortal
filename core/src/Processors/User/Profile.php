<?php

namespace App\Processors\User;

use App\Processors\Processor;
use Slim\Http\Response;

class Profile extends Processor
{

    protected $scope = 'profile';


    /**
     * @return Response
     */
    public function get()
    {
        $data = null;
        if ($user = $this->container->user) {
            $data = [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'fullname' => $user->fullname,
                'phone' => $user->phone,
                'scope' => $user->role->scope,
                'role_id' => $user->role_id,
            ];
        }

        return $this->success([
            'user' => $data,
        ]);
    }


    /**
     * @return Response
     */
    public function patch()
    {
        $this->container->user->fill([
            'email' => trim($this->getProperty('email')),
            'fullname' => trim($this->getProperty('fullname')),
            'phone' => preg_replace('#[^0-9]#', '', $this->getProperty('phone')),
        ]);
        if ($password = trim($this->getProperty('password'))) {
            $this->container->user->password = $password;
        }
        $this->container->user->save();

        return $this->get();
    }
}