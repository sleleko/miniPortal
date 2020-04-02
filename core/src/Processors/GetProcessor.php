<?php

namespace App\Processors;

use Slim\Http\Response;

abstract class GetProcessor extends ObjectProcessor
{

    /**
     * @return Response
     */
    public function post()
    {
        return $this->failure('Wrong method requested');
    }


    /**
     * @return Response
     */
    public function put()
    {
        return $this->failure('Wrong method requested');
    }


    /**
     * @return Response
     */
    public function patch()
    {
        return $this->failure('Wrong method requested');
    }


    /**
     * @return Response
     */
    public function delete()
    {
        return $this->failure('Wrong method requested');
    }

}