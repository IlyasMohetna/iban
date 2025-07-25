<?php

namespace IlyasMohetna\Iban\Tests;

use IlyasMohetna\Iban\Exceptions\UnsupportedCountryCodeException;
use IlyasMohetna\Iban\Registry\IBANRegistry;
use IlyasMohetna\Iban\Registry\IBANRegistryInterface;

describe('IBANRegistry Instantiation', function (): void {
    it('can be instantiated', function (): void {
        $registry = new IBANRegistry;
        expect($registry)->toBeInstanceOf(IBANRegistry::class);
        expect($registry)->toBeInstanceOf(IBANRegistryInterface::class);
    });

    it('loads registry data on construction', function (): void {
        $registry = new IBANRegistry;
        // Should not throw exception if data loads correctly
        expect($registry->isCountrySupported('DE'))->toBeTrue();
    });

    it('validates registry data structure on construction', function (): void {
        // If registry data is malformed, constructor should throw exception
        $registry = new IBANRegistry;
        expect($registry)->toBeInstanceOf(IBANRegistry::class);
    });
});

describe('IBANRegistry Country Support', function (): void {
    beforeEach(function (): void {
        $this->registry = new IBANRegistry;
    });

    it('supports common countries', function (): void {
        expect($this->registry->isCountrySupported('DE'))->toBeTrue(); // Germany
        expect($this->registry->isCountrySupported('GB'))->toBeTrue(); // United Kingdom
        expect($this->registry->isCountrySupported('FR'))->toBeTrue(); // France
        expect($this->registry->isCountrySupported('ES'))->toBeTrue(); // Spain
        expect($this->registry->isCountrySupported('IT'))->toBeTrue(); // Italy
        expect($this->registry->isCountrySupported('NL'))->toBeTrue(); // Netherlands
        expect($this->registry->isCountrySupported('BE'))->toBeTrue(); // Belgium
        expect($this->registry->isCountrySupported('AT'))->toBeTrue(); // Austria
        expect($this->registry->isCountrySupported('CH'))->toBeTrue(); // Switzerland
    });

    it('does not support invalid countries', function (): void {
        expect($this->registry->isCountrySupported('XX'))->toBeFalse();
        expect($this->registry->isCountrySupported('ZZ'))->toBeFalse();
        expect($this->registry->isCountrySupported(''))->toBeFalse();
        expect($this->registry->isCountrySupported('ABC'))->toBeFalse(); // Too long
    });

    it('is case sensitive for country codes', function (): void {
        expect($this->registry->isCountrySupported('DE'))->toBeTrue();
        expect($this->registry->isCountrySupported('de'))->toBeFalse();
        expect($this->registry->isCountrySupported('De'))->toBeFalse();
    });
});

describe('IBANRegistry Country Data', function (): void {
    beforeEach(function (): void {
        $this->registry = new IBANRegistry;
    });

    it('returns complete country data for supported countries', function (): void {
        $data = $this->registry->getCountryData('DE');

        expect($data)->toBeArray();
        expect($data)->toHaveKey('iban_regex');
        expect($data)->toHaveKey('iban_length');
        expect($data)->toHaveKey('bban_structure');
        expect($data['iban_regex'])->toBeString();
        expect($data['iban_length'])->toBeInt();
        expect($data['bban_structure'])->toBeString();
    });

    it('throws exception for unsupported countries', function (): void {
        expect(fn () => $this->registry->getCountryData('XX'))
            ->toThrow(UnsupportedCountryCodeException::class);
    });

    it('returns correct data structure for multiple countries', function (): void {
        $countries = ['DE', 'GB', 'FR', 'ES', 'IT'];

        foreach ($countries as $country) {
            $data = $this->registry->getCountryData($country);
            expect($data)->toBeArray();
            expect($data['iban_length'])->toBeInt();
            expect($data['iban_length'])->toBeGreaterThan(14);
            expect($data['iban_length'])->toBeLessThanOrEqual(34);
        }
    });
});

describe('IBANRegistry Specific Methods', function (): void {
    beforeEach(function (): void {
        $this->registry = new IBANRegistry;
    });

    it('returns IBAN regex for supported countries', function (): void {
        $regex = $this->registry->getIbanRegex('DE');
        expect($regex)->toBeString();
        expect($regex)->toContain('/^DE');
        expect($regex)->toContain('\\d{2}'); // Checksum digits
    });

    it('returns IBAN length for supported countries', function (): void {
        expect($this->registry->getIbanLength('DE'))->toBe(22);
        expect($this->registry->getIbanLength('GB'))->toBe(22);
        expect($this->registry->getIbanLength('FR'))->toBe(27);
        expect($this->registry->getIbanLength('NO'))->toBe(15); // Shortest IBAN
    });

    it('returns BBAN structure for supported countries', function (): void {
        $structure = $this->registry->getBbanStructure('DE');
        expect($structure)->toBeString();
        expect($structure)->not()->toBeEmpty();

        $structure2 = $this->registry->getBbanStructure('GB');
        expect($structure2)->toBeString();
        expect($structure2)->not()->toBeEmpty();
    });

    it('throws exception for unsupported countries in specific methods', function (): void {
        expect(fn () => $this->registry->getIbanRegex('XX'))
            ->toThrow(UnsupportedCountryCodeException::class);

        expect(fn () => $this->registry->getIbanLength('XX'))
            ->toThrow(UnsupportedCountryCodeException::class);

        expect(fn () => $this->registry->getBbanStructure('XX'))
            ->toThrow(UnsupportedCountryCodeException::class);
    });
});

describe('IBANRegistry Real Data Validation', function (): void {
    beforeEach(function (): void {
        $this->registry = new IBANRegistry;
    });

    it('has correct data for Germany', function (): void {
        $data = $this->registry->getCountryData('DE');
        expect($data['iban_length'])->toBe(22);
        expect($data['iban_regex'])->toContain('DE');
        expect($data['bban_structure'])->toBeString();
    });

    it('has correct data for United Kingdom', function (): void {
        $data = $this->registry->getCountryData('GB');
        expect($data['iban_length'])->toBe(22);
        expect($data['iban_regex'])->toContain('GB');
        expect($data['bban_structure'])->toBeString();
    });

    it('has correct data for France', function (): void {
        $data = $this->registry->getCountryData('FR');
        expect($data['iban_length'])->toBe(27);
        expect($data['iban_regex'])->toContain('FR');
        expect($data['bban_structure'])->toBeString();
    });

    it('validates regex patterns are properly formatted', function (): void {
        $countries = ['DE', 'GB', 'FR', 'ES', 'IT', 'NL', 'BE', 'AT', 'CH'];

        foreach ($countries as $country) {
            $regex = $this->registry->getIbanRegex($country);
            expect($regex)->toStartWith('/^'.$country);
            expect($regex)->toEndWith('$/');

            // Test that the regex is valid
            expect(@preg_match($regex, ''))->not()->toBeFalse();
        }
    });

    it('validates IBAN lengths are within valid range', function (): void {
        $countries = ['DE', 'GB', 'FR', 'ES', 'IT', 'NL', 'BE', 'AT', 'CH', 'NO'];

        foreach ($countries as $country) {
            $length = $this->registry->getIbanLength($country);
            expect($length)->toBeGreaterThanOrEqual(15); // Minimum IBAN length
            expect($length)->toBeLessThanOrEqual(34); // Maximum IBAN length
        }
    });

    it('has bank identifier position for relevant countries', function (): void {
        $data = $this->registry->getCountryData('DE');
        expect($data)->toHaveKey('bank_identifier_position');

        // Some countries might not have bank_identifier_position, which is optional
        if (isset($data['bank_identifier_position'])) {
            expect($data['bank_identifier_position'])->toBeString();
        }
    });
});

describe('IBANRegistry Edge Cases', function (): void {
    beforeEach(function (): void {
        $this->registry = new IBANRegistry;
    });

    it('handles empty country code', function (): void {
        expect($this->registry->isCountrySupported(''))->toBeFalse();
    });

    it('handles null-like country codes', function (): void {
        expect($this->registry->isCountrySupported('00'))->toBeFalse();
        expect($this->registry->isCountrySupported('  '))->toBeFalse();
    });

    it('handles numeric country codes', function (): void {
        expect($this->registry->isCountrySupported('12'))->toBeFalse();
        expect($this->registry->isCountrySupported('99'))->toBeFalse();
    });

    it('handles special character country codes', function (): void {
        expect($this->registry->isCountrySupported('@@'))->toBeFalse();
        expect($this->registry->isCountrySupported('--'))->toBeFalse();
    });

    it('tests comprehensive country coverage', function (): void {
        // Test that we have good coverage of real countries
        $majorCountries = [
            'DE', 'GB', 'FR', 'ES', 'IT', 'NL', 'BE', 'AT', 'CH', 'NO',
            'SE', 'DK', 'FI', 'PL', 'CZ', 'HU', 'PT', 'GR', 'IE', 'LU',
        ];

        $supportedCount = 0;
        foreach ($majorCountries as $country) {
            if ($this->registry->isCountrySupported($country)) {
                $supportedCount++;
            }
        }

        // We should support most major European countries
        expect($supportedCount)->toBeGreaterThan(15);
    });
});
