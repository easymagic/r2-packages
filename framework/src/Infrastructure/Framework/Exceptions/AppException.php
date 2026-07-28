<?php 
namespace R2Packages\Framework\Infrastructure\Framework\Exceptions;

use Exception;
use Throwable;

class AppException extends Exception
{
    public function __construct(string $message = "", int $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function create(string $message = "", int $code = 0, $previous = null)
    {
        return new self($message, $code, $previous);
    }
}