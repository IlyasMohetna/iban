<?php

namespace IlyasMohetna\Iban\Exceptions;

use Exception;

class InvalidIBANException extends Exception
{
    /**
     * Create a new InvalidIBANException instance.
     */
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
