<?php

namespace IlyasMohetna\Iban;

use IlyasMohetna\Iban\Registry\IBANRegistry;
use IlyasMohetna\Iban\Exceptions\InvalidIBANException;
use IlyasMohetna\Iban\Enums\Format;

class IBAN
{
    private const COUNTRY_CODE_OFFSET = 0;
    private const COUNTRY_CODE_LENGTH = 2;
    private const CHECKSUM_OFFSET = 2;
    private const CHECKSUM_LENGTH = 2;
    private const BBAN_OFFSET = 4;

    private string $iban;
    private string $normalizedIban;
    private string $countryCode;
    private string $checksum;
    private string $bban;
    private ?string $bankCode = null;
    private IBANRegistry $registry;

    public function __construct(string $iban, ?IBANRegistry $registry = null)
    {
        $this->iban = $iban;
        $this->registry = $registry ?? new IBANRegistry();
        $this->normalizedIban = $this->normalize();
        $this->validate();
        $this->setProperties();
    }

    /**
     * Normalize the IBAN: remove spaces, convert to uppercase, and validate format.
     */
    private function normalize(): string
    {
        $normalized = strtoupper(trim($this->iban));
        $normalized = preg_replace('/[^A-Z0-9]/', '', $normalized);

        if (empty($normalized)) {
            throw new InvalidIBANException("The IBAN '{$this->iban}' cannot be normalized.");
        }

        return $normalized;
    }

    /**
     * Validate the IBAN using registry rules.
     */
    private function validate(): void
    {
        $this->countryCode = $this->extractCountryCode();
        $this->checksum = $this->extractChecksum();
        $this->bban = $this->extractBBAN();

        if (!$this->registry->isCountrySupported($this->countryCode)) {
            throw new InvalidIBANException("Country code '{$this->countryCode}' is not supported.");
        }

        $this->validateLength();
        $this->validateChecksum();
        $this->validateRegex();
    }

    /**
     * Extract properties after validation.
     */
    private function setProperties(): void
    {
        $this->bankCode = $this->extractBankCode();
    }

    /**
     * Validate IBAN length.
     */
    private function validateLength(): void
    {
        $expectedLength = $this->registry->getIbanLength($this->countryCode);

        if (strlen($this->normalizedIban) !== $expectedLength) {
            throw new InvalidIBANException("The IBAN '{$this->iban}' has an incorrect length for country '{$this->countryCode}'.");
        }
    }

    /**
     * Validate the IBAN checksum using Mod 97-10.
     */
    private function validateChecksum(): void
    {
        $rearranged = substr($this->normalizedIban, self::BBAN_OFFSET) . substr($this->normalizedIban, 0, self::BBAN_OFFSET);
        $numericRepresentation = $this->convertToNumeric($rearranged);

        if (bcmod($numericRepresentation, '97') !== '1') {
            throw new InvalidIBANException("The checksum for IBAN '{$this->iban}' is invalid.");
        }
    }

    /**
     * Convert IBAN to numeric representation.
     */
    private function convertToNumeric(string $iban): string
    {
        $converted = '';
        foreach (str_split($iban) as $char) {
            $converted .= ctype_alpha($char) ? (ord($char) - 55) : $char;
        }

        return $converted;
    }

    /**
     * Validate IBAN format using regex from the registry.
     */
    private function validateRegex(): void
    {
        $regex = $this->registry->getIbanRegex($this->countryCode);

        if (!preg_match($regex, $this->normalizedIban)) {
            throw new InvalidIBANException("The IBAN '{$this->iban}' does not match the required format for country '{$this->countryCode}'.");
        }
    }

    /**
     * Extract country code.
     */
    private function extractCountryCode(): string
    {
        return substr($this->normalizedIban, self::COUNTRY_CODE_OFFSET, self::COUNTRY_CODE_LENGTH);
    }

    /**
     * Extract checksum.
     */
    private function extractChecksum(): string
    {
        return substr($this->normalizedIban, self::CHECKSUM_OFFSET, self::CHECKSUM_LENGTH);
    }

    /**
     * Extract BBAN.
     */
    private function extractBBAN(): string
    {
        return substr($this->normalizedIban, self::BBAN_OFFSET);
    }

    /**
     * Extract bank code from BBAN using registry rules.
     */
    private function extractBankCode(): ?string
    {
        $countryData = $this->registry->getCountryData($this->countryCode);
        $bankIdentifierPosition = $countryData['bank_identifier_position'] ?? null;

        if (!$bankIdentifierPosition || !preg_match('/^(\d+)-(\d+)$/', $bankIdentifierPosition, $matches)) {
            return null; // Return null if the position string is missing or invalid
        }

        [$fullMatch, $start, $end] = $matches;

        // Calculate the length and extract the bank code
        $length = $end - $start + 1;
        return substr($this->bban, $start - 1, $length);
    }

    /**
     * Format the IBAN in the specified style.
     */
    public function format(Format $format = Format::PRINT): string
    {
        return match ($format) {
            Format::PRINT => wordwrap($this->normalizedIban, 4, ' ', true),
            Format::ELECTRONIC => $this->normalizedIban,
            Format::ANONYMIZED => str_pad(substr($this->normalizedIban, -4), strlen($this->normalizedIban), 'X', STR_PAD_LEFT),
        };
    }

    // Public getters for accessing properties
    public function getBankCode(): ?string { return $this->bankCode; }
    public function getCountryCode(): string { return $this->countryCode; }
    public function getChecksum(): string { return $this->checksum; }
    public function getBBAN(): string { return $this->bban; }
}
