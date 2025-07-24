<?php

namespace IlyasMohetna\Iban\Constants;

/**
 * Constants for IBAN processing
 */
final class IBANConstants
{
    // IBAN structure offsets and lengths
    public const COUNTRY_CODE_OFFSET = 0;

    public const COUNTRY_CODE_LENGTH = 2;

    public const CHECKSUM_OFFSET = 2;

    public const CHECKSUM_LENGTH = 2;

    public const BBAN_OFFSET = 4;

    // IBAN length constraints
    public const MAX_IBAN_LENGTH = 34;

    public const MIN_IBAN_LENGTH = 15;

    // Checksum calculation
    public const MODULO_97 = '97';

    public const VALID_CHECKSUM_REMAINDER = '1';

    // Character to number mapping base
    public const ALPHA_TO_NUMBER_OFFSET = 55; // 'A' = 65, we want it to be 10, so 65-55=10

    private function __construct()
    {
        // Prevent instantiation
    }
}
