<?php

namespace App\Processors\Web;

use App\Processors\GetProcessor;

class Test extends GetProcessor
{

    public function get()
    {
        return $this->success('Hello World!');
    }

}