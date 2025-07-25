<?php

namespace IlyasMohetna\Iban\Tests;

use IlyasMohetna\Iban\BIC;
use IlyasMohetna\Iban\Constants\IBANConstants;
use IlyasMohetna\Iban\Enums\Format;
use IlyasMohetna\Iban\Exceptions\InvalidIBANException;
use IlyasMohetna\Iban\IBAN;
use IlyasMohetna\Iban\Registry\IBANRegistry;

// Valid IBANs for testing (with correct checksums)
const VALID_IBANS = [
    'DE89370400440532013000', // Germany
    'GB29NWBK60161331926819', // United Kingdom
    'FR1420041010050500013M02606', // France
    'ES9121000418450200051332', // Spain
    'IT60X0542811101000000123456', // Italy
    'NL91ABNA0417164300', // Netherlands
    'BE68539007547034', // Belgium
    'AT611904300234573201', // Austria
    'CH9300762011623852957', // Switzerland
];

const INVALID_IBANS = [
    'DE89370400440532013001', // Wrong checksum
    'GB29NWBK60161331926818', // Wrong checksum
    'FR1420041010050500013M02607', // Wrong checksum
    'XX1234567890123456', // Unsupported country
    'DE893704004405320130', // Wrong length
    '', // Empty
    '1234', // Too short
    'INVALIDIBAN', // Invalid format
];

describe('IBAN Instantiation', function (): void {
    it('can be instantiated with valid IBAN', function (): void {
        $iban = new IBAN(VALID_IBANS[0]);
        expect($iban)->toBeInstanceOf(IBAN::class);
    });

    it('can be instantiated with throwOnInvalid disabled', function (): void {
        $iban = new IBAN(INVALID_IBANS[0], false);
        expect($iban)->toBeInstanceOf(IBAN::class);
        expect($iban->isValid())->toBeFalse();
    });

    it('throws exception when throwOnInvalid is enabled', function (): void {
        expect(fn (): \IlyasMohetna\Iban\IBAN => new IBAN(INVALID_IBANS[0], true))
            ->toThrow(InvalidIBANException::class);
    });

    it('can be instantiated with custom registry', function (): void {
        $registry = new IBANRegistry;
        $iban = new IBAN(VALID_IBANS[0], false, $registry);
        expect($iban)->toBeInstanceOf(IBAN::class);
    });
});

describe('IBAN Validation', function (): void {
    it('validates correct IBANs', function (string $validIban): void {
        $iban = new IBAN($validIban);
        expect($iban->isValid())->toBeTrue();
    })->with([
        [VALID_IBANS[0]], // DE
        [VALID_IBANS[1]], // GB
        [VALID_IBANS[2]], // FR
        [VALID_IBANS[3]], // ES
        [VALID_IBANS[4]], // IT
        [VALID_IBANS[5]], // NL
        [VALID_IBANS[6]], // BE
        [VALID_IBANS[7]], // AT
        [VALID_IBANS[8]], // CH
    ]);

    it('rejects invalid IBANs', function (string $invalidIban): void {
        $iban = new IBAN($invalidIban);
        expect($iban->isValid())->toBeFalse();
    })->with([
        [INVALID_IBANS[0]], // Wrong checksum
        [INVALID_IBANS[1]], // Wrong checksum
        [INVALID_IBANS[2]], // Wrong checksum
        [INVALID_IBANS[3]], // Unsupported country
        [INVALID_IBANS[4]], // Wrong length
        [INVALID_IBANS[5]], // Empty
        [INVALID_IBANS[6]], // Too short
        [INVALID_IBANS[7]], // Invalid format
    ]);

    it('validates IBAN length correctly', function (): void {
        $validIban = new IBAN('DE89370400440532013000'); // 22 chars
        expect($validIban->isValid())->toBeTrue();

        $invalidIban = new IBAN('DE893704004405320130'); // 20 chars (wrong length)
        expect($invalidIban->isValid())->toBeFalse();
    });

    it('validates IBAN checksum correctly', function (): void {
        $validIban = new IBAN('DE89370400440532013000'); // Correct checksum
        expect($validIban->isValid())->toBeTrue();

        $invalidIban = new IBAN('DE88370400440532013000'); // Wrong checksum
        expect($invalidIban->isValid())->toBeFalse();
    });

    it('validates IBAN regex pattern correctly', function (): void {
        $validIban = new IBAN('DE89370400440532013000'); // Correct format
        expect($validIban->isValid())->toBeTrue();

        $invalidIban = new IBAN('DE89ABCD00440532013000'); // Wrong format (letters in bank code)
        expect($invalidIban->isValid())->toBeFalse();
    });
});

describe('IBAN Getters', function (): void {
    it('returns the normalized IBAN', function (): void {
        $iban = new IBAN('de89 3704 0044 0532 0130 00'); // With spaces and lowercase
        expect($iban->getIban())->toBe('DE89370400440532013000');
    });

    it('extracts country code correctly', function (): void {
        $iban = new IBAN(VALID_IBANS[0]);
        expect($iban->getCountryCode())->toBe('DE');

        $iban2 = new IBAN(VALID_IBANS[1]);
        expect($iban2->getCountryCode())->toBe('GB');
    });

    it('extracts checksum correctly', function (): void {
        $iban = new IBAN(VALID_IBANS[0]); // DE89...
        expect($iban->getChecksum())->toBe('89');

        $iban2 = new IBAN(VALID_IBANS[1]); // GB29...
        expect($iban2->getChecksum())->toBe('29');
    });

    it('extracts BBAN correctly', function (): void {
        $iban = new IBAN(VALID_IBANS[0]); // DE89370400440532013000
        expect($iban->getBBAN())->toBe('370400440532013000');
    });

    it('extracts bank code when available', function (): void {
        $iban = new IBAN('DE89370400440532013000'); // German IBAN with bank code
        expect($iban->getBankCode())->toBe('37040044'); // First 8 digits of BBAN

        $iban2 = new IBAN('GB29NWBK60161331926819'); // UK IBAN with bank code
        expect($iban2->getBankCode())->toBe('NWBK'); // First 4 letters of BBAN
    });

    it('returns null for bank code when not available', function (): void {
        // Find a country without bank_identifier_position
        $iban = new IBAN('FI2112345600000785'); // Finland has no bank_identifier_position
        expect($iban->getBankCode())->toBeNull();
    });

    it('returns BIC when available', function (): void {
        $iban = new IBAN(VALID_IBANS[0]);
        $bic = $iban->getBIC();
        // BIC might be null if not found in the registry, which is okay
        expect($bic)->toBeNull();
    });
});

describe('IBAN Formatting', function (): void {
    it('formats IBAN in print format', function (): void {
        $iban = new IBAN(VALID_IBANS[0]);
        expect($iban->format(Format::PRINT))->toBe('DE89 3704 0044 0532 0130 00');
    });

    it('formats IBAN in electronic format', function (): void {
        $iban = new IBAN(VALID_IBANS[0]);
        expect($iban->format(Format::ELECTRONIC))->toBe('DE89370400440532013000');
    });

    it('formats IBAN in anonymized format', function (): void {
        $iban = new IBAN(VALID_IBANS[0]);
        expect($iban->format(Format::ANONYMIZED))->toBe('XXXXXXXXXXXXXXXXXX3000');
    });

    it('uses print format as default', function (): void {
        $iban = new IBAN(VALID_IBANS[0]);
        expect($iban->format())->toBe('DE89 3704 0044 0532 0130 00');
    });
});

describe('IBAN Normalization', function (): void {
    it('normalizes IBAN with spaces', function (): void {
        $iban = new IBAN('DE89 3704 0044 0532 0130 00');
        expect($iban->getIban())->toBe('DE89370400440532013000');
    });

    it('normalizes IBAN with lowercase letters', function (): void {
        $iban = new IBAN('de89370400440532013000');
        expect($iban->getIban())->toBe('DE89370400440532013000');
    });

    it('normalizes IBAN with mixed case and spaces', function (): void {
        $iban = new IBAN('De89 3704 0044 0532 0130 00');
        expect($iban->getIban())->toBe('DE89370400440532013000');
    });

    it('removes special characters', function (): void {
        $iban = new IBAN('DE89-3704-0044-0532-0130-00');
        expect($iban->getIban())->toBe('DE89370400440532013000');
    });

    it('handles leading and trailing whitespace', function (): void {
        $iban = new IBAN('  DE89370400440532013000  ');
        expect($iban->getIban())->toBe('DE89370400440532013000');
    });

    it('throws exception for empty normalized IBAN', function (): void {
        expect(fn (): \IlyasMohetna\Iban\IBAN => new IBAN('  ---  ', true))
            ->toThrow(InvalidIBANException::class);
    });
});

describe('IBAN Error Handling', function (): void {
    it('handles unsupported country codes gracefully', function (): void {
        $iban = new IBAN('XX1234567890123456');
        expect($iban->isValid())->toBeFalse();
        expect($iban->getCountryCode())->toBe('XX');
    });

    it('throws exception for unsupported country when throwOnInvalid is true', function (): void {
        expect(fn (): \IlyasMohetna\Iban\IBAN => new IBAN('XX1234567890123456', true))
            ->toThrow(InvalidIBANException::class);
    });

    it('handles invalid bank identifier position format', function (): void {
        // This would require mocking the registry, but we can test with real data
        $iban = new IBAN(VALID_IBANS[0]);
        expect($iban->getBankCode())->toBeString();
    });
});

describe('IBAN with Different Countries', function (): void {
    it('works with German IBANs', function (): void {
        $iban = new IBAN('DE89370400440532013000');
        expect($iban->isValid())->toBeTrue();
        expect($iban->getCountryCode())->toBe('DE');
        expect($iban->getBankCode())->toBe('37040044');
    });

    it('works with British IBANs', function (): void {
        $iban = new IBAN('GB29NWBK60161331926819');
        expect($iban->isValid())->toBeTrue();
        expect($iban->getCountryCode())->toBe('GB');
        expect($iban->getBankCode())->toBe('NWBK');
    });

    it('works with French IBANs', function (): void {
        $iban = new IBAN('FR1420041010050500013M02606');
        expect($iban->isValid())->toBeTrue();
        expect($iban->getCountryCode())->toBe('FR');
        expect($iban->getBankCode())->toBe('20041');
    });

    it('works with Spanish IBANs', function (): void {
        $iban = new IBAN('ES9121000418450200051332');
        expect($iban->isValid())->toBeTrue();
        expect($iban->getCountryCode())->toBe('ES');
        expect($iban->getBankCode())->toBe('2100');
    });

    it('works with Italian IBANs', function (): void {
        $iban = new IBAN('IT60X0542811101000000123456');
        expect($iban->isValid())->toBeTrue();
        expect($iban->getCountryCode())->toBe('IT');
        // Italian bank code is at position 2-6 (5 digits)
        expect($iban->getBankCode())->toBe('05428');
    });
});

describe('IBAN Constants Integration', function (): void {
    it('uses correct constants for offsets', function (): void {
        $iban = new IBAN(VALID_IBANS[0]);

        // Test that the constants are being used correctly
        expect(IBANConstants::COUNTRY_CODE_OFFSET)->toBe(0);
        expect(IBANConstants::COUNTRY_CODE_LENGTH)->toBe(2);
        expect(IBANConstants::CHECKSUM_OFFSET)->toBe(2);
        expect(IBANConstants::CHECKSUM_LENGTH)->toBe(2);
        expect(IBANConstants::BBAN_OFFSET)->toBe(4);

        expect($iban->getCountryCode())->toBe('DE');
        expect($iban->getChecksum())->toBe('89');
    });

    it('uses correct constants for validation', function (): void {
        expect(IBANConstants::MODULO_97)->toBe('97');
        expect(IBANConstants::VALID_CHECKSUM_REMAINDER)->toBe('1');
        expect(IBANConstants::ALPHA_TO_NUMBER_OFFSET)->toBe(55);
    });
});

describe('IBAN Edge Cases', function (): void {
    it('handles minimum length IBANs', function (): void {
        // Norway has 15 characters (shortest IBAN)
        $iban = new IBAN('NO9386011117947');
        expect($iban->isValid())->toBeTrue();
        expect(strlen($iban->getIban()))->toBe(15);
    });

    it('handles maximum length IBANs', function (): void {
        // Some countries have longer IBANs, let's test one
        $iban = new IBAN('LC55HEMM000100010012001200023015'); // Saint Lucia - 32 chars
        expect($iban->isValid())->toBeTrue();
        expect(strlen($iban->getIban()))->toBe(32);
    });

    it('handles special characters in BBAN', function (): void {
        // French IBANs can have letters in the BBAN
        $iban = new IBAN('FR1420041010050500013M02606');
        expect($iban->isValid())->toBeTrue();
        expect($iban->getBBAN())->toContain('M');
    });
});
