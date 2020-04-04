<?php namespace HappyMonkey\WebSocket\Exceptions;

use Throwable;

class MalformedJsonException extends \Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct("Malformed JSON", $code, $previous);
    }

}