<?php

namespace App\Request;

use InvalidArgumentException;

class InvalidResourceIdException extends InvalidArgumentException
{
    public function __construct()
    {
        $this->message = "Invalid ID";
    }
}
