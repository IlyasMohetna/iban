<?php

namespace IlyasMohetna\Iban\Registry;

use IlyasMohetna\Iban\Exceptions\UnsupportedCountryCodeException;

class BICRegistry
{
    private readonly string $dataPath;

    public function __construct()
    {
        // Define the base path to the BIC data directory
        $this->dataPath = __DIR__.'/../Data/bic/';
    }

    /**
     * Check if a country has BIC data available.
     */
    public function isCountrySupported(string $countryCode): bool
    {
        $filePath = $this->getFilePath($countryCode);

        return is_readable($filePath) && file_exists($filePath);
    }

    /**
     * Load BIC data for a specific country.
     *
     * @return array<int, array<string, mixed>>
     *
     * @throws UnsupportedCountryCodeException
     */
    public function loadCountryData(string $countryCode): array
    {
        if (! $this->isCountrySupported($countryCode)) {
            return [];
        }

        return include $this->getFilePath($countryCode);
    }

    /**
     * Get BIC data by Code for a specific country.
     *
     * @return array<string, mixed>|null
     */
    public function getBICByCode(string $countryCode, string $bic): ?array
    {
        $countryData = $this->loadCountryData($countryCode);

        foreach ($countryData as $entry) {
            if ($entry['bic'] === $bic) {
                return $entry;
            }
        }

        return null; // Return null if no matching BIC is found
    }

    /**
     * Get BIC data by Bank Code for a specific country.
     *
     * @return array<string, mixed>|null
     */
    public function getBICByBankCode(string $countryCode, string $bankCode): ?array
    {
        $countryData = $this->loadCountryData($countryCode);

        foreach ($countryData as $entry) {
            if ($entry['bank_code'] === $bankCode) {
                return $entry;
            }
        }

        return null; // Return null if no matching BIC is found
    }

    /**
     * Get the path to the PHP array file for a specific country.
     */
    private function getFilePath(string $countryCode): string
    {
        return $this->dataPath.strtoupper($countryCode).'.php';
    }
}
