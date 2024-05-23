<?php

namespace App\Exceptions;

use Exception;

class NotAvailableException extends Exception
{
    protected $message = "The product is out of stock";
    protected $code = 404;

    public function render()
    {
        return response([
            'message' => $this->message,
            'code' => $this->code
        ], $this->code);
    }
}
