<?php

namespace IlyasMohetna\Iban\Registry;

/**
 * Interface for BIC registry implementations
 */
interface BICRegistryInterface
{
    /**
     * Check if a country has BIC data available.
     */
    public function isCountrySupported(string $countryCode): bool;

    /**
     * Load BIC data for a specific country.
     *
     * @return array<int, array<string, mixed>>
     */
    public function loadCountryData(string $countryCode): array;

    /**
     * Get BIC data by Code for a specific country.
     *
     * @return array<string, mixed>|null
     */
    public function getBICByCode(string $countryCode, string $bic): ?array;

    /**
     * Get BIC data by Bank Code for a specific country.
     *
     * @return array<string, mixed>|null
     */
    public function getBICByBankCode(string $countryCode, string $bankCode): ?array;
}
