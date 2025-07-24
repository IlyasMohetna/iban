<?php

namespace IlyasMohetna\Iban\Registry;

use IlyasMohetna\Iban\Exceptions\UnsupportedCountryCodeException;

/**
 * Interface for IBAN registry implementations
 */
interface IBANRegistryInterface
{
    /**
     * Check if a country is supported in the registry.
     */
    public function isCountrySupported(string $countryCode): bool;

    /**
     * Get IBAN metadata for a specific country.
     *
     * @return array{
     *     iban_regex: string,
     *     iban_length: int,
     *     bban_structure: string,
     *     bank_identifier_position?: string
     * }
     *
     * @throws UnsupportedCountryCodeException
     */
    public function getCountryData(string $countryCode): array;

    /**
     * Get the IBAN regex for a specific country.
     *
     * @throws UnsupportedCountryCodeException
     */
    public function getIbanRegex(string $countryCode): string;

    /**
     * Get the IBAN length for a specific country.
     *
     * @throws UnsupportedCountryCodeException
     */
    public function getIbanLength(string $countryCode): int;

    /**
     * Get the BBAN structure for a specific country.
     *
     * @throws UnsupportedCountryCodeException
     */
    public function getBbanStructure(string $countryCode): string;
}
