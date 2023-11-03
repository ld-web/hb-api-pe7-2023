<?php

namespace App\Response;

class ResponseCode
{
    // 2xx
    public const OK = 200;
    public const CREATED = 201;
    public const NO_CONTENT = 204;

    // 4xx
    public const BAD_REQUEST = 400;
    public const NOT_FOUND = 404;

    // 5xx
    public const INTERNAL_SERVER_ERROR = 500;
}
