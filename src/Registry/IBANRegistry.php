<?php

namespace IlyasMohetna\Iban\Registry;

use IlyasMohetna\Iban\Exceptions\UnsupportedCountryCodeException;

class IBANRegistry implements IBANRegistryInterface
{
    /**
     * @var array<string, array{
     *     iban_regex: string,
     *     iban_length: int,
     *     bban_structure: string,
     *     bank_identifier_position?: string
     * }>
     */
    private array $registry;

    public function __construct()
    {
        // Load IBAN registry from the Data folder
        $this->registry = require __DIR__.'/../Data/iban/registry.php';

        // Validate that each country data has all required keys
        foreach ($this->registry as $countryCode => $data) {
            foreach (['iban_regex', 'iban_length', 'bban_structure'] as $key) {
                if (! array_key_exists($key, $data)) {
                    throw new \InvalidArgumentException("Missing key '{$key}' for country '{$countryCode}' in IBAN registry.");
                }
            }
            // 'bank_identifier_position' is optional
        }
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
     * @return array{
     *     iban_regex: string,
     *     iban_length: int,
     *     bban_structure: string,
     *     bank_identifier_position?: string
     * }
     *
     * @throws UnsupportedCountryCodeException
     */
    public function getCountryData(string $countryCode): array
    {
        if (! $this->isCountrySupported($countryCode)) {
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

        return $countryData['iban_regex'];
    }

    /**
     * Get the IBAN length for a specific country.
     */
    public function getIbanLength(string $countryCode): int
    {
        $countryData = $this->getCountryData($countryCode);

        return $countryData['iban_length'];
    }

    /**
     * Get the BBAN structure for a specific country.
     */
    public function getBbanStructure(string $countryCode): string
    {
        $countryData = $this->getCountryData($countryCode);

        return $countryData['bban_structure'];
    }
}
