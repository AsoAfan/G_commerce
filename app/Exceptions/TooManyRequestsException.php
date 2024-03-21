<?php

namespace App\Exceptions;

use Exception;

class TooManyRequestsException extends Exception
{

    protected $code = 429;
    protected $message = "Too many requests, try again later";

    public function report()
    {

    }

    public function render($request)
    {
        return response([
            'message' => $this->message,
            'code' => $this->code
        ], $this->code);
    }
}
