<?php

namespace IlyasMohetna\Iban\Exceptions;

use Exception;

class UnsupportedCountryCodeException extends Exception
{
    /**
     * Create a new UnsupportedCountryCodeException instance.
     */
    public function __construct(string $countryCode)
    {
        $message = "The country code '{$countryCode}' is not supported in the IBAN registry.";
        parent::__construct($message);
    }
}
