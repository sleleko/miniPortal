<?php

namespace App\Processors\Error;

use App\Processors\Processor;
use Slim\Http\Response;

class NotFound extends Processor
{
    /**
     * @return Response
     */
    public function process()
    {
        return $this->failure('Unknown method requested', 404);
    }
}