<?php

namespace IlyasMohetna\Iban\Registry;

use IlyasMohetna\Iban\Exceptions\UnsupportedCountryCodeException;

class IBANRegistry
{
    private array $registry;

    public function __construct()
    {
        // Load IBAN registry from the Data folder
        $this->registry = require __DIR__ . '/../Data/iban_registry.php';
    }

    /**
     * Check if a country is supported in the registry.
     */
    public function isCountrySupported(string $countryCode): bool
    {
        return isset($this->registry[$countryCode]);
    }

    /**
     * Get IBAN metadata for a specific country.
     *
     * @throws UnsupportedCountryCodeException
     */
    public function getCountryData(string $countryCode): array
    {
        if (!$this->isCountrySupported($countryCode)) {
            throw new UnsupportedCountryCodeException("Country code '{$countryCode}' is not supported.");
        }

        return $this->registry[$countryCode];
    }

    /**
     * Get the IBAN regex for a specific country.
     */
    public function getIbanRegex(string $countryCode): string
    {
        $countryData = $this->getCountryData($countryCode);
        return $countryData['iban_regex'] ?? '';
    }

    /**
     * Get the IBAN length for a specific country.
     */
    public function getIbanLength(string $countryCode): int
    {
        $countryData = $this->getCountryData($countryCode);
        return $countryData['iban_length'] ?? 0;
    }

    /**
     * Get the BBAN structure for a specific country.
     */
    public function getBbanStructure(string $countryCode): string
    {
        $countryData = $this->getCountryData($countryCode);
        return $countryData['bban_structure'] ?? '';
    }
}
