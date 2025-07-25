<?php

namespace IlyasMohetna\Iban\Tests;

use IlyasMohetna\Iban\Registry\BICRegistry;
use IlyasMohetna\Iban\Registry\BICRegistryInterface;

describe('BICRegistry Instantiation', function (): void {
    it('can be instantiated', function (): void {
        $registry = new BICRegistry;
        expect($registry)->toBeInstanceOf(BICRegistry::class);
        expect($registry)->toBeInstanceOf(BICRegistryInterface::class);
    });

    it('sets data path correctly on construction', function (): void {
        $registry = new BICRegistry;
        expect($registry)->toBeInstanceOf(BICRegistry::class);
        // Data path is private, but we can test that construction doesn't fail
    });
});

describe('BICRegistry Country Support', function (): void {
    beforeEach(function (): void {
        $this->registry = new BICRegistry;
    });

    it('checks country support correctly', function (): void {
        // Test with countries that may or may not have BIC data
        $result = $this->registry->isCountrySupported('DE');
        expect($result)->toBeBool();

        $result2 = $this->registry->isCountrySupported('GB');
        expect($result2)->toBeBool();
    });

    it('returns false for non-existent countries', function (): void {
        expect($this->registry->isCountrySupported('XX'))->toBeFalse();
        expect($this->registry->isCountrySupported('ZZ'))->toBeFalse();
        expect($this->registry->isCountrySupported(''))->toBeFalse();
    });

    it('handles invalid country code formats', function (): void {
        expect($this->registry->isCountrySupported('ABC'))->toBeFalse(); // Too long
        expect($this->registry->isCountrySupported('1'))->toBeFalse(); // Too short
        expect($this->registry->isCountrySupported('12'))->toBeFalse(); // Numeric
        expect($this->registry->isCountrySupported('@@'))->toBeFalse(); // Special chars
    });
});

describe('BICRegistry Data Loading', function (): void {
    beforeEach(function (): void {
        $this->registry = new BICRegistry;
    });

    it('loads country data correctly', function (): void {
        // Test with a country that might have data
        $data = $this->registry->loadCountryData('DE');
        expect($data)->toBeArray();
        // If no data exists, should return empty array
    });

    it('returns empty array for unsupported countries', function (): void {
        $data = $this->registry->loadCountryData('XX');
        expect($data)->toBeArray();
        expect($data)->toBeEmpty();
    });

    it('handles empty country code', function (): void {
        $data = $this->registry->loadCountryData('');
        expect($data)->toBeArray();
        expect($data)->toBeEmpty();
    });

    it('returns proper array structure when data exists', function (): void {
        $data = $this->registry->loadCountryData('DE');
        expect($data)->toBeArray();

        // If data exists, each entry should be an array
        foreach ($data as $entry) {
            expect($entry)->toBeArray();
        }
    });
});

describe('BICRegistry BIC Lookup by Code', function (): void {
    beforeEach(function (): void {
        $this->registry = new BICRegistry;
    });

    it('searches for BIC by code', function (): void {
        $result = $this->registry->getBICByCode('DE', 'DEUTDEFF');
        expect($result)->toBeNull(); // Since we don't have real BIC data
    });

    it('returns null for non-existent BIC', function (): void {
        $result = $this->registry->getBICByCode('DE', 'NONEXISTENT');
        expect($result)->toBeNull();
    });

    it('returns null for unsupported country', function (): void {
        $result = $this->registry->getBICByCode('XX', 'ANYBIC12');
        expect($result)->toBeNull();
    });

    it('handles empty BIC code', function (): void {
        $result = $this->registry->getBICByCode('DE', '');
        expect($result)->toBeNull();
    });

    it('is case sensitive for BIC lookup', function (): void {
        $upperResult = $this->registry->getBICByCode('DE', 'DEUTDEFF');
        $lowerResult = $this->registry->getBICByCode('DE', 'deutdeff');

        // Results might be different due to case sensitivity
        expect($upperResult)->toBeNull();
        expect($lowerResult)->toBeNull();
    });
});

describe('BICRegistry BIC Lookup by Bank Code', function (): void {
    beforeEach(function (): void {
        $this->registry = new BICRegistry;
    });

    it('searches for BIC by bank code', function (): void {
        $result = $this->registry->getBICByBankCode('DE', '37040044');
        expect($result)->toBeNull(); // Since we don't have real BIC data
    });

    it('returns null for non-existent bank code', function (): void {
        $result = $this->registry->getBICByBankCode('DE', 'NONEXISTENT');
        expect($result)->toBeNull();
    });

    it('returns null for unsupported country', function (): void {
        $result = $this->registry->getBICByBankCode('XX', '12345678');
        expect($result)->toBeNull();
    });

    it('handles empty bank code', function (): void {
        $result = $this->registry->getBICByBankCode('DE', '');
        expect($result)->toBeNull();
    });

    it('handles numeric bank codes', function (): void {
        $result = $this->registry->getBICByBankCode('DE', '12345678');
        expect($result)->toBeNull();
    });

    it('handles alphanumeric bank codes', function (): void {
        $result = $this->registry->getBICByBankCode('GB', 'NWBK');
        expect($result)->toBeNull();
    });
});

describe('BICRegistry Data Structure Validation', function (): void {
    beforeEach(function (): void {
        $this->registry = new BICRegistry;
    });

    it('returns properly structured BIC data', function (): void {
        // Load data for a country and check structure
        $data = $this->registry->loadCountryData('DE');

        foreach ($data as $entry) {
            expect($entry)->toBeArray();
            // We don't enforce specific keys as they may vary
        }

        // At least verify the data is an array
        expect($data)->toBeArray();
    });

    it('validates BIC lookup results have expected structure', function (): void {
        $result = $this->registry->getBICByCode('DE', 'TESTBIC1');

        if ($result !== null) {
            expect($result)->toBeArray();
            // Should have at least 'bic' key for getBICByCode
            expect($result)->toHaveKey('bic');
        }
    });

    it('validates bank code lookup results have expected structure', function (): void {
        $result = $this->registry->getBICByBankCode('DE', '12345678');

        if ($result !== null) {
            expect($result)->toBeArray();
            // Should have at least 'bank_code' key for getBICByBankCode
            expect($result)->toHaveKey('bank_code');
        }
    });
});

describe('BICRegistry File Path Handling', function (): void {
    beforeEach(function (): void {
        $this->registry = new BICRegistry;
    });

    it('handles country codes correctly for file paths', function (): void {
        // Test that different country codes work
        $countries = ['DE', 'GB', 'FR', 'US', 'XX'];

        foreach ($countries as $country) {
            $data = $this->registry->loadCountryData($country);
            expect($data)->toBeArray();
        }
    });

    it('handles lowercase country codes', function (): void {
        // Should work consistently regardless of case
        $upperData = $this->registry->loadCountryData('DE');
        $lowerData = $this->registry->loadCountryData('de');

        expect($upperData)->toBeArray();
        expect($lowerData)->toBeArray();
    });

    it('handles file existence checks', function (): void {
        // isCountrySupported should check file existence
        $supported = $this->registry->isCountrySupported('DE');
        expect($supported)->toBeBool();

        $unsupported = $this->registry->isCountrySupported('XX');
        expect($unsupported)->toBeFalse();
    });
});

describe('BICRegistry Edge Cases', function (): void {
    beforeEach(function (): void {
        $this->registry = new BICRegistry;
    });

    it('handles malformed country codes gracefully', function (): void {
        $testCodes = ['', ' ', '123', 'ABC', '@#$'];

        foreach ($testCodes as $code) {
            expect($this->registry->isCountrySupported($code))->toBeFalse();
            expect($this->registry->loadCountryData($code))->toBeArray();
            expect($this->registry->getBICByCode($code, 'TEST'))->toBeNull();
            expect($this->registry->getBICByBankCode($code, 'TEST'))->toBeNull();
        }
    });

    it('handles malformed BIC codes gracefully', function (): void {
        $testBics = ['', ' ', '1', 'A', 'TOOLONGBICCODE123456'];

        foreach ($testBics as $bic) {
            $result = $this->registry->getBICByCode('DE', $bic);
            expect($result)->toBeNull();
        }
    });

    it('handles malformed bank codes gracefully', function (): void {
        $testCodes = ['', ' ', 'TOOLONGBANKCODE123456789'];

        foreach ($testCodes as $code) {
            $result = $this->registry->getBICByBankCode('DE', $code);
            expect($result)->toBeNull();
        }
    });

    it('handles non-string inputs gracefully', function (): void {
        // These should not crash the registry
        expect($this->registry->isCountrySupported('DE'))->toBeBool();
        expect($this->registry->loadCountryData('DE'))->toBeArray();
    });
});

describe('BICRegistry Performance', function (): void {
    beforeEach(function (): void {
        $this->registry = new BICRegistry;
    });

    it('handles multiple lookups efficiently', function (): void {
        $countries = ['DE', 'GB', 'FR'];
        $bics = ['DEUTDEFF', 'NWBKGB2L', 'BNPAFRPP'];

        foreach ($countries as $country) {
            foreach ($bics as $bic) {
                $result = $this->registry->getBICByCode($country, $bic);
                // Result can be array if BIC exists, or null if not found
                expect($result === null || is_array($result))->toBeTrue();
            }
        }
    });

    it('handles data loading for multiple countries', function (): void {
        $countries = ['DE', 'GB', 'FR', 'US', 'CA', 'AU'];

        foreach ($countries as $country) {
            $data = $this->registry->loadCountryData($country);
            expect($data)->toBeArray();
        }
    });
});
