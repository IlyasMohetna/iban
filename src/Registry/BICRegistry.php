<?php

namespace IlyasMohetna\Iban\Registry;

use IlyasMohetna\Iban\Exceptions\UnsupportedCountryCodeException;

class BICRegistry
{
    private string $dataPath;

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

        return file_exists($filePath);
    }

    /**
     * Load BIC data for a specific country.
     *
     * @throws UnsupportedCountryCodeException
     */
    public function loadCountryData(string $countryCode): array
    {
        if (! $this->isCountrySupported($countryCode)) {
            throw new UnsupportedCountryCodeException("BIC data for country code '{$countryCode}' is not available.");
        }

        $filePath = $this->getFilePath($countryCode);

        // Include the PHP file and return the data
        $data = include $filePath;

        if (! is_array($data)) {
            throw new \RuntimeException("BIC data for country code '{$countryCode}' must return an array.");
        }

        return $data;
    }

    /**
     * Get BIC data by bank code for a specific country.
     */
    public function getBICByBankCode(string $countryCode, string $bankCode): ?array
    {
        $countryData = $this->loadCountryData($countryCode);

        foreach ($countryData as $bicEntry) {
            if ($bicEntry['bank_code'] === $bankCode) {
                return $bicEntry;
            }
        }

        return null; // Return null if no matching BIC found
    }

    /**
     * Get the path to the PHP array file for a specific country.
     */
    private function getFilePath(string $countryCode): string
    {
        return $this->dataPath.strtoupper($countryCode).'.php';
    }
}
