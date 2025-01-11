<?php

namespace IlyasMohetna\Iban\Exceptions;

use Exception;

/**
 * Exception thrown when a BIC is invalid.
 */
class InvalidBICException extends Exception
{
    public function __construct(string $message = 'The provided BIC is invalid.', int $code = 0, ?Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
