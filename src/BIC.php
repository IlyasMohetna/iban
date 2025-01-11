<?php

namespace IlyasMohetna\Iban;

use IlyasMohetna\Iban\Exceptions\InvalidBICException;
use IlyasMohetna\Iban\Registry\BICRegistry;

class BIC
{
    private readonly string $bic;

    private string $bankCode;

    private string $countryCode;

    private ?string $name = null;

    private ?string $shortName = null;

    private bool $primary = false;

    public function __construct(string $bic, private readonly BICRegistry $registry = new BICRegistry)
    {
        $this->bic = strtoupper(trim($bic));
        $this->loadDetails();
    }

    /**
     * Static factory method to create a BIC from bankCode and countryCode.
     */
    public static function fromBankCode(string $bankCode, string $countryCode): ?self
    {
        $registry = new BICRegistry;
        $bicData = $registry->getBICByBankCode($countryCode, $bankCode);

        if ($bicData === null) {
            return null; // No matching BIC found
        }

        return new self(
            $bicData['bic']
        );
    }

    /**
     * Validate and parse the BIC into its components.
     *
     * @throws InvalidBICException
     */
    private function loadDetails(): void
    {
        $countryCode = substr($this->bic, 4, 2); // Extract the country code
        $details = $this->registry->getBICByCode($countryCode, $this->bic);

        if ($details === null) {
            throw new InvalidBICException("BIC '{$this->bic}' not found in the registry.");
        }
        // Parse components
        $this->bankCode = $details['bank_code'];
        $this->countryCode = $details['country_code'];
        $this->name = $details['name'];
        $this->shortName = $details['short_name'];
        $this->primary = $details['primary'];
    }

    /**
     * Getter methods for BIC properties.
     */
    public function getBIC(): string
    {
        return $this->bic;
    }

    public function getBankCode(): string
    {
        return $this->bankCode;
    }

    public function getCountryCode(): string
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
