<?php

namespace IlyasMohetna\Iban\Exceptions;

use Exception;

class UnsupportedCountryCodeException extends Exception
{
    /**
     * Create a new UnsupportedCountryCodeException instance.
     */
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
