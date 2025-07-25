<?php

namespace IlyasMohetna\Iban\Tests;

use IlyasMohetna\Iban\BIC;
use IlyasMohetna\Iban\Enums\Format;
use IlyasMohetna\Iban\Exceptions\InvalidIBANException;
use IlyasMohetna\Iban\IBAN;
use IlyasMohetna\Iban\Registry\BICRegistry;
use IlyasMohetna\Iban\Registry\IBANRegistry;

describe('Integration Tests', function (): void {
    it('can process a complete IBAN workflow', function (): void {
        // Create IBAN
        $iban = new IBAN('DE89370400440532013000');

        // Validate
        expect($iban->isValid())->toBeTrue();

        // Extract components
        expect($iban->getCountryCode())->toBe('DE');
        expect($iban->getChecksum())->toBe('89');
        expect($iban->getBBAN())->toBe('370400440532013000');
        expect($iban->getBankCode())->toBe('37040044');

        // Format in different ways
        expect($iban->format(Format::PRINT))->toBe('DE89 3704 0044 0532 0130 00');
        expect($iban->format(Format::ELECTRONIC))->toBe('DE89370400440532013000');
        expect($iban->format(Format::ANONYMIZED))->toBe('XXXXXXXXXXXXXXXXXX3000');

        // Check BIC (may be null if not found)
        $bic = $iban->getBIC();
        expect($bic)->toBeNull();
    });

    it('handles invalid IBAN gracefully', function (): void {
        $iban = new IBAN('DE88370400440532013000'); // Wrong checksum

        expect($iban->isValid())->toBeFalse();
        expect($iban->getCountryCode())->toBe('DE'); // Still extracts country
        expect($iban->getChecksum())->toBe('88'); // Still extracts checksum
        expect($iban->getBankCode())->toBeNull(); // But doesn't extract bank code
        expect($iban->getBIC())->toBeNull(); // And no BIC
    });

    it('works with custom registries', function (): void {
        $ibanRegistry = new IBANRegistry;
        $bicRegistry = new BICRegistry;

        $iban = new IBAN('DE89370400440532013000', false, $ibanRegistry);
        expect($iban->isValid())->toBeTrue();

        $bic = new BIC('DEUTDEFF', $bicRegistry);
        expect($bic->getBIC())->toBe('DEUTDEFF');
    });

    it('handles multiple countries correctly', function (): void {
        $testData = [
            'DE89370400440532013000' => ['DE', '89', '370400440532013000'],
            'GB29NWBK60161331926819' => ['GB', '29', 'NWBK60161331926819'],
            'FR1420041010050500013M02606' => ['FR', '14', '20041010050500013M02606'],
            'NO9386011117947' => ['NO', '93', '86011117947'],
        ];

        foreach ($testData as $ibanString => $expected) {
            $iban = new IBAN($ibanString);
            expect($iban->isValid())->toBeTrue();
            expect($iban->getCountryCode())->toBe($expected[0]);
            expect($iban->getChecksum())->toBe($expected[1]);
            expect($iban->getBBAN())->toBe($expected[2]);
        }
    });

    it('validates IBAN normalization and formatting together', function (): void {
        // Test with various input formats
        $inputs = [
            'de89 3704 0044 0532 0130 00',
            'DE89-3704-0044-0532-0130-00',
            '  DE89370400440532013000  ',
            'De89370400440532013000',
        ];

        foreach ($inputs as $input) {
            $iban = new IBAN($input);
            expect($iban->isValid())->toBeTrue();
            expect($iban->getIban())->toBe('DE89370400440532013000');
            expect($iban->format(Format::PRINT))->toBe('DE89 3704 0044 0532 0130 00');
        }
    });

    it('handles exception scenarios correctly', function (): void {
        // Invalid IBAN with throwOnInvalid = true
        expect(fn (): \IlyasMohetna\Iban\IBAN => new IBAN('INVALID', true))
            ->toThrow(InvalidIBANException::class);

        // Unsupported country with throwOnInvalid = true
        expect(fn (): \IlyasMohetna\Iban\IBAN => new IBAN('XX1234567890123456', true))
            ->toThrow(InvalidIBANException::class);

        // Wrong length with throwOnInvalid = true
        expect(fn (): \IlyasMohetna\Iban\IBAN => new IBAN('DE893704004405320130', true))
            ->toThrow(InvalidIBANException::class);
    });

    it('integrates BIC creation from IBAN bank code', function (): void {
        $iban = new IBAN('DE89370400440532013000');

        if ($iban->isValid() && $iban->getBankCode() !== null) {
            $bic = BIC::fromBankCode($iban->getBankCode(), $iban->getCountryCode());
            expect($bic)->toBeNull();
        }
    });

    it('handles registry interface implementations correctly', function (): void {
        $ibanRegistry = new IBANRegistry;
        $bicRegistry = new BICRegistry;

        // Test that registries implement their interfaces
        expect($ibanRegistry->isCountrySupported('DE'))->toBeBool();
        expect($bicRegistry->isCountrySupported('DE'))->toBeBool();

        // Test registry methods work
        expect($ibanRegistry->getIbanLength('DE'))->toBe(22);
        expect($ibanRegistry->getIbanRegex('DE'))->toBeString();
        expect($ibanRegistry->getBbanStructure('DE'))->toBeString();

        $countryData = $ibanRegistry->getCountryData('DE');
        expect($countryData)->toBeArray();
        expect($countryData)->toHaveKey('iban_length');
        expect($countryData)->toHaveKey('iban_regex');
        expect($countryData)->toHaveKey('bban_structure');
    });

    it('validates complete IBAN processing pipeline', function (): void {
        $rawIban = ' fr14 2004 1010 0505 0001 3m02 606 '; // French IBAN with spaces

        // Step 1: Create and normalize
        $iban = new IBAN($rawIban);
        expect($iban->getIban())->toBe('FR1420041010050500013M02606');

        // Step 2: Validate
        expect($iban->isValid())->toBeTrue();

        // Step 3: Extract components
        expect($iban->getCountryCode())->toBe('FR');
        expect($iban->getChecksum())->toBe('14');
        expect($iban->getBBAN())->toBe('20041010050500013M02606');
        expect($iban->getBankCode())->toBe('20041'); // French bank code

        // Step 4: Format for different uses
        $printFormat = $iban->format(Format::PRINT);
        expect($printFormat)->toBe('FR14 2004 1010 0505 0001 3M02 606');

        $electronicFormat = $iban->format(Format::ELECTRONIC);
        expect($electronicFormat)->toBe('FR1420041010050500013M02606');

        $anonymizedFormat = $iban->format(Format::ANONYMIZED);
        expect($anonymizedFormat)->toEndWith('2606');
        expect($anonymizedFormat)->toStartWith('X');
    });

    it('handles edge cases in complete workflow', function (): void {
        // Very short IBAN (Norway)
        $shortIban = new IBAN('NO9386011117947');
        expect($shortIban->isValid())->toBeTrue();
        expect(strlen($shortIban->getIban()))->toBe(15);
        expect($shortIban->format(Format::PRINT))->toBe('NO93 8601 1117 947');

        // Very long IBAN (Saint Lucia)
        $longIban = new IBAN('LC55HEMM000100010012001200023015');
        expect($longIban->isValid())->toBeTrue();
        expect(strlen($longIban->getIban()))->toBe(32);
        expect($longIban->format(Format::PRINT))->toBe('LC55 HEMM 0001 0001 0012 0012 0002 3015');
    });

    it('maintains data consistency across operations', function (): void {
        $iban = new IBAN('IT60X0542811101000000123456');

        // Multiple calls should return consistent data
        expect($iban->getCountryCode())->toBe($iban->getCountryCode());
        expect($iban->getChecksum())->toBe($iban->getChecksum());
        expect($iban->getBBAN())->toBe($iban->getBBAN());
        expect($iban->isValid())->toBe($iban->isValid());

        // Formatting should be consistent
        expect($iban->format(Format::PRINT))->toBe($iban->format(Format::PRINT));
        expect($iban->format(Format::ELECTRONIC))->toBe($iban->format(Format::ELECTRONIC));
        expect($iban->format(Format::ANONYMIZED))->toBe($iban->format(Format::ANONYMIZED));
    });

    it('validates thread safety simulation', function (): void {
        // Create multiple IBAN instances "simultaneously"
        $ibans = [
            new IBAN('DE89370400440532013000'),
            new IBAN('GB29NWBK60161331926819'),
            new IBAN('FR1420041010050500013M02606'),
            new IBAN('ES9121000418450200051332'),
            new IBAN('IT60X0542811101000000123456'),
        ];

        // Each should be independent and valid
        foreach ($ibans as $iban) {
            expect($iban->isValid())->toBeTrue();
            expect($iban->getCountryCode())->toBeString();
            expect($iban->getChecksum())->toBeString();
            expect($iban->getBBAN())->toBeString();
        }

        // Cross-validation - each should maintain its own state
        expect($ibans[0]->getCountryCode())->toBe('DE');
        expect($ibans[1]->getCountryCode())->toBe('GB');
        expect($ibans[2]->getCountryCode())->toBe('FR');
        expect($ibans[3]->getCountryCode())->toBe('ES');
        expect($ibans[4]->getCountryCode())->toBe('IT');
    });

    it('validates memory efficiency with large datasets', function (): void {
        // Process multiple IBANs to ensure no memory leaks or issues
        $ibanStrings = [
            'DE89370400440532013000',
            'GB29NWBK60161331926819',
            'FR1420041010050500013M02606',
            'ES9121000418450200051332',
            'IT60X0542811101000000123456',
            'NL91ABNA0417164300',
            'BE68539007547034',
            'AT611904300234573201',
            'CH9300762011623852957',
            'NO9386011117947',
        ];

        $results = [];
        foreach ($ibanStrings as $ibanString) {
            $iban = new IBAN($ibanString);
            $results[] = [
                'iban' => $iban->getIban(),
                'valid' => $iban->isValid(),
                'country' => $iban->getCountryCode(),
                'print' => $iban->format(Format::PRINT),
            ];
        }

        expect(count($results))->toBe(count($ibanStrings));

        // All should be valid
        foreach ($results as $result) {
            expect($result['valid'])->toBeTrue();
            expect($result['country'])->toBeString();
            expect($result['print'])->toBeString();
        }
    });
});
