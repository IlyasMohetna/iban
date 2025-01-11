<?php

namespace IlyasMohetna\Iban;

use IlyasMohetna\Iban\Enums\Format;
use IlyasMohetna\Iban\Exceptions\InvalidIBANException;
use IlyasMohetna\Iban\Registry\IBANRegistry;

class IBAN
{
    private const COUNTRY_CODE_OFFSET = 0;

    private const COUNTRY_CODE_LENGTH = 2;

    private const CHECKSUM_OFFSET = 2;

    private const CHECKSUM_LENGTH = 2;

    private const BBAN_OFFSET = 4;
    private bool $isValid = false;

    private string $normalizedIban;

    private ?string $countryCode = null;

    private ?string $checksum = null;

    private ?string $bban = null;

    private ?string $bankCode = null;

    private ?BIC $bic = null;

    public function __construct(private readonly string $iban, private readonly bool $throwOnInvalid = false, private readonly IBANRegistry $registry = new IBANRegistry)
    {
        try {
            $this->normalizedIban = $this->normalize();
            $this->validate();
            $this->setProperties();
            $this->initializeBIC();
            $this->isValid = true; // If no exceptions, IBAN is valid
        } catch (InvalidIBANException $e) {
            $this->isValid = false; // Mark IBAN as invalid
            if ($this->throwOnInvalid) {
                throw $e; // Re-throw exception if enabled
            }
        }
    }

    private function normalize(): string
    {
        $normalized = strtoupper(trim($this->iban));
        $normalized = preg_replace('/[^A-Z0-9]/', '', $normalized);

        if (empty($normalized)) {
            throw new InvalidIBANException("The IBAN '{$this->iban}' cannot be normalized.");
        }

        return $normalized;
    }

    private function validate(): void
    {
        $this->countryCode = $this->extractCountryCode();
        $this->checksum = $this->extractChecksum();
        $this->bban = $this->extractBBAN();

        if (! $this->registry->isCountrySupported($this->countryCode)) {
            throw new InvalidIBANException("Country code '{$this->countryCode}' is not supported.");
        }

        $this->validateLength();
        $this->validateChecksum();
        $this->validateRegex();
    }

    private function setProperties(): void
    {
        $this->bankCode = $this->extractBankCode();
    }

    private function validateLength(): void
    {
        $expectedLength = $this->registry->getIbanLength($this->countryCode);

        if (strlen($this->normalizedIban) !== $expectedLength) {
            throw new InvalidIBANException("The IBAN '{$this->iban}' has an incorrect length for country '{$this->countryCode}'.");
        }
    }

    private function validateChecksum(): void
    {
        $rearranged = substr($this->normalizedIban, self::BBAN_OFFSET).substr($this->normalizedIban, 0, self::BBAN_OFFSET);
        $numericRepresentation = $this->convertToNumeric($rearranged);

        if (bcmod($numericRepresentation, '97') !== '1') {
            throw new InvalidIBANException("The checksum for IBAN '{$this->iban}' is invalid.");
        }
    }

    private function convertToNumeric(string $iban): string
    {
        $converted = '';
        foreach (str_split($iban) as $char) {
            $converted .= ctype_alpha($char) ? (ord($char) - 55) : $char;
        }

        return $converted;
    }

    private function validateRegex(): void
    {
        $regex = $this->registry->getIbanRegex($this->countryCode);

        if (in_array(preg_match($regex, $this->normalizedIban), [0, false], true)) {
            throw new InvalidIBANException("The IBAN '{$this->iban}' does not match the required format for country '{$this->countryCode}'.");
        }
    }

    private function extractCountryCode(): string
    {
        return substr($this->normalizedIban, self::COUNTRY_CODE_OFFSET, self::COUNTRY_CODE_LENGTH);
    }

    private function extractChecksum(): string
    {
        return substr($this->normalizedIban, self::CHECKSUM_OFFSET, self::CHECKSUM_LENGTH);
    }

    private function extractBBAN(): string
    {
        return substr($this->normalizedIban, self::BBAN_OFFSET);
    }

    private function extractBankCode(): ?string
    {
        $countryData = $this->registry->getCountryData($this->countryCode);
        $bankIdentifierPosition = $countryData['bank_identifier_position'] ?? null;

        if (! $bankIdentifierPosition || in_array(preg_match('/^(\d+)-(\d+)$/', (string) $bankIdentifierPosition, $matches), [0, false], true)) {
            return null;
        }

        [$fullMatch, $start, $end] = $matches;
        $length = $end - $start + 1;

        return substr((string) $this->bban, $start - 1, $length);
    }

    public function format(Format $format = Format::PRINT): string
    {
        return match ($format) {
            Format::PRINT => wordwrap($this->normalizedIban, 4, ' ', true),
            Format::ELECTRONIC => $this->normalizedIban,
            Format::ANONYMIZED => str_pad(substr($this->normalizedIban, -4), strlen($this->normalizedIban), 'X', STR_PAD_LEFT),
        };
    }

    private function initializeBIC(): void
    {
        dump('called');
        if ($this->bankCode && $this->countryCode) {
            $this->bic = BIC::fromBankCode($this->bankCode, $this->countryCode);
        }
    }

    // Public getters
    public function getBankCode(): ?string
    {
        return $this->bankCode;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function getChecksum(): ?string
    {
        return $this->checksum;
    }

    public function getBBAN(): ?string
    {
        return $this->bban;
    }

    public function getBIC(): ?BIC
    {
        return $this->bic;
    }

    public function isValid(): bool
    {
        return $this->isValid;
    }
}
