<?php

namespace App;

use App\Response\ResponseCode;
use Throwable;

class ErrorHandler
{
    public static function registerExceptionHandler(): void
    {
        set_exception_handler(function (Throwable $ex) {
            http_response_code(ResponseCode::INTERNAL_SERVER_ERROR);
            echo json_encode([
                'error' => 'Une erreur est survenue'
            ]);
            exit;
        });
    }
}
