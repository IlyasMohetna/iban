<?php

namespace IlyasMohetna\Iban;

use IlyasMohetna\Iban\Registry\BICRegistry;
use IlyasMohetna\Iban\Registry\BICRegistryInterface;

class BIC
{
    private readonly string $bic;

    private ?string $bankCode = null;

    private ?string $countryCode = null;

    private ?string $name = null;

    private ?string $shortName = null;

    private bool $primary = false;

    public function __construct(string $bic, private readonly BICRegistryInterface $registry = new BICRegistry)
    {
        $this->bic = strtoupper(trim($bic));
        $this->loadDetails();
    }

    /**
     * Static factory method to create a BIC from bankCode and countryCode.
     * Returns null if no matching BIC is found or if an error occurs.
     */
    public static function fromBankCode(string $bankCode, string $countryCode, ?BICRegistryInterface $registry = null): ?self
    {
        $registry = $registry ?? new BICRegistry;
        $bicData = $registry->getBICByBankCode($countryCode, $bankCode);

        if ($bicData === null) {
            return null; // No matching BIC found
        }

        $bicVal = $bicData['bic'] ?? null;
        if (! is_string($bicVal)) {
            return null; // Invalid BIC format
        }

        try {
            return new self($bicVal, $registry);
        } catch (\Exception) {
            // Log the exception if necessary
            return null; // Return null instead of throwing
        }
    }

    /**
     * Validate and parse the BIC into its components.
     * Sets properties to null if data is missing or invalid.
     */
    private function loadDetails(): void
    {
        try {
            $countryCode = substr($this->bic, 4, 2); // Extract the country code
            $details = $this->registry->getBICByCode($countryCode, $this->bic);

            if ($details === null) {
                // BIC not found; properties remain null
                return;
            }

            // Parse components with validation
            $bankCode = $details['bank_code'] ?? null;
            if (is_string($bankCode)) {
                $this->bankCode = $bankCode;
            }

            $countryCode = $details['country_code'] ?? null;
            if (is_string($countryCode)) {
                $this->countryCode = $countryCode;
            }

            $name = $details['name'] ?? null;
            $this->name = is_string($name) ? $name : null;

            $shortName = $details['short_name'] ?? null;
            $this->shortName = is_string($shortName) ? $shortName : null;

            $this->primary = isset($details['primary']) && (bool) $details['primary'];
        } catch (\Exception) {
            // Handle any unexpected exceptions gracefully
            // Optionally, log the exception
            $this->bankCode = null;
            $this->countryCode = null;
            $this->name = null;
            $this->shortName = null;
            $this->primary = false;
        }
    }

    /**
     * Getter methods for BIC properties.
     */
    public function getBIC(): string
    {
        return $this->bic;
    }

    public function getBankCode(): ?string
    {
        return $this->bankCode;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getShortName(): ?string
    {
        return $this->shortName;
    }

    public function isPrimary(): bool
    {
        return $this->primary;
    }
}
