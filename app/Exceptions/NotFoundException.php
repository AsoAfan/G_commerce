<?php

namespace App\Exceptions;

use Exception;

class NotFoundException extends Exception
{
    protected $code = 404;
    protected $message = "Not found";

    public function render()
    {
        return response([
            'message' => $this->message,
            'code' => $this->code

        ], $this->code);
    }
}
