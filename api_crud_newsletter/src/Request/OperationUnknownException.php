<?php

namespace App\Request;

use LogicException;

class OperationUnknownException extends LogicException
{
    public function __construct()
    {
        $this->message = "Operation not supported";
    }
}
