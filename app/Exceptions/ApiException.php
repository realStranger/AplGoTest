<?php

namespace App\Exceptions;

use Exception;
use ReflectionClass;

class ApiException extends Exception
{
    public const BAD_REQUEST = 400;
    public const UNAUTHORIZED = 401;
    public const FORBIDDEN = 403;
    public const NOT_FOUND = 404;
    public const VALIDATION_ERROR = 422;
    public const INTERNAL_ERROR = 500;

    private $error_code;
    private $reason;

    public function __construct(string $reason, int $error_code, int $code = 500, Exception $previous = null)
    {
        parent::__construct($reason, $code, $previous);
        $this->error_code = $error_code;
        $this->reason = $reason;
    }

    public function getErrorCode()
    {
        return $this->error_code;
    }

    public static function getErrorMessage($code)
    {
        $self = new ReflectionClass(__CLASS__);
        $constants = array_flip($self->getConstants());
        return $constants[$code];
    }

    public function getReason()
    {
        return $this->reason;
    }
}
