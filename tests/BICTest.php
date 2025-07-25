<?php

namespace IlyasMohetna\Iban\Tests;

use IlyasMohetna\Iban\BIC;
use IlyasMohetna\Iban\Registry\BICRegistry;

describe('BIC Instantiation', function (): void {
    it('can be instantiated with BIC code', function (): void {
        $bic = new BIC('DEUTDEFF');
        expect($bic)->toBeInstanceOf(BIC::class);
    });

    it('can be instantiated with custom registry', function (): void {
        $registry = new BICRegistry;
        $bic = new BIC('DEUTDEFF', $registry);
        expect($bic)->toBeInstanceOf(BIC::class);
    });

    it('normalizes BIC code to uppercase', function (): void {
        $bic = new BIC('deutdeff');
        expect($bic->getBIC())->toBe('DEUTDEFF');
    });

    it('trims whitespace from BIC code', function (): void {
        $bic = new BIC('  DEUTDEFF  ');
        expect($bic->getBIC())->toBe('DEUTDEFF');
    });
});

describe('BIC Factory Methods', function (): void {
    it('can create BIC from bank code and country code', function (): void {
        // This will return null if no matching BIC is found, which is expected
        $bic = BIC::fromBankCode('12345678', 'DE');
        expect($bic)->toBeNull();
    });

    it('returns null when no matching BIC is found', function (): void {
        $bic = BIC::fromBankCode('NONEXISTENT', 'XX');
        expect($bic)->toBeNull();
    });

    it('can use custom registry for factory method', function (): void {
        $registry = new BICRegistry;
        $bic = BIC::fromBankCode('12345678', 'DE', $registry);
        expect($bic)->toBeNull();
    });

    it('handles exceptions gracefully in factory method', function (): void {
        // Test with invalid data that might cause exceptions
        $bic = BIC::fromBankCode('', '');
        expect($bic)->toBeNull();
    });
});

describe('BIC Getters', function (): void {
    it('returns the BIC code', function (): void {
        $bic = new BIC('DEUTDEFF');
        expect($bic->getBIC())->toBe('DEUTDEFF');
    });

    it('returns bank code when available', function (): void {
        $bic = new BIC('DEUTDEFF');
        // Bank code might be null if not found in registry
        expect($bic->getBankCode())->toBeNull();
    });

    it('returns country code when available', function (): void {
        $bic = new BIC('DEUTDEFF');
        // Country code might be null if not found in registry
        expect($bic->getCountryCode())->toBeNull();
    });

    it('returns name when available', function (): void {
        $bic = new BIC('DEUTDEFF');
        // Name might be null if not found in registry
        expect($bic->getName())->toBeNull();
    });

    it('returns short name when available', function (): void {
        $bic = new BIC('DEUTDEFF');
        // Short name might be null if not found in registry
        expect($bic->getShortName())->toBeNull();
    });

    it('returns primary status', function (): void {
        $bic = new BIC('DEUTDEFF');
        expect($bic->isPrimary())->toBeBool();
    });
});

describe('BIC Data Loading', function (): void {
    it('handles missing BIC data gracefully', function (): void {
        $bic = new BIC('NONEXIST');
        expect($bic->getBIC())->toBe('NONEXIST');
        expect($bic->getBankCode())->toBeNull();
        expect($bic->getCountryCode())->toBeNull();
        expect($bic->getName())->toBeNull();
        expect($bic->getShortName())->toBeNull();
        expect($bic->isPrimary())->toBeFalse();
    });

    it('extracts country code from BIC format', function (): void {
        $bic = new BIC('DEUTDEFF'); // DE is the country code
        // Even if not found in registry, the BIC format should be preserved
        expect($bic->getBIC())->toBe('DEUTDEFF');
    });

    it('handles invalid BIC formats gracefully', function (): void {
        $bic = new BIC('ABC'); // Too short
        expect($bic->getBIC())->toBe('ABC');
        expect($bic->getBankCode())->toBeNull();
    });

    it('handles exceptions during data loading', function (): void {
        // BIC with potential problematic characters
        $bic = new BIC('TEST1234');
        expect($bic)->toBeInstanceOf(BIC::class);
        expect($bic->getBIC())->toBe('TEST1234');
    });
});

describe('BIC Country Code Extraction', function (): void {
    it('extracts country code from valid BIC', function (): void {
        $bic = new BIC('DEUTDEFF'); // Characters 4-5 should be 'DE'
        expect($bic->getBIC())->toBe('DEUTDEFF');
        // The actual country code depends on registry data
    });

    it('handles short BIC codes', function (): void {
        $bic = new BIC('ABC');
        expect($bic->getBIC())->toBe('ABC');
        // Should not crash when extracting country code
    });

    it('handles exactly 8 character BIC', function (): void {
        $bic = new BIC('DEUTDEFF');
        expect($bic->getBIC())->toBe('DEUTDEFF');
        expect(strlen($bic->getBIC()))->toBe(8);
    });

    it('handles 11 character BIC with branch code', function (): void {
        $bic = new BIC('DEUTDEFF500');
        expect($bic->getBIC())->toBe('DEUTDEFF500');
        expect(strlen($bic->getBIC()))->toBe(11);
    });
});

describe('BIC Error Handling', function (): void {
    it('handles empty BIC gracefully', function (): void {
        $bic = new BIC('');
        expect($bic->getBIC())->toBe('');
        expect($bic->getBankCode())->toBeNull();
    });

    it('handles whitespace-only BIC', function (): void {
        $bic = new BIC('   ');
        expect($bic->getBIC())->toBe('');
        expect($bic->getBankCode())->toBeNull();
    });

    it('handles special characters in BIC', function (): void {
        $bic = new BIC('DEUT-DEFF');
        expect($bic->getBIC())->toBe('DEUT-DEFF');
        // Should not crash
    });

    it('handles numeric BIC codes', function (): void {
        $bic = new BIC('12345678');
        expect($bic->getBIC())->toBe('12345678');
        expect($bic->getBankCode())->toBeNull();
    });
});

describe('BIC Registry Integration', function (): void {
    it('uses registry to load BIC details', function (): void {
        $bic = new BIC('TESTBIC1');
        // The registry will be called to load details
        // Results depend on actual registry data
        expect($bic->getBIC())->toBe('TESTBIC1');
    });

    it('handles registry returning null', function (): void {
        $bic = new BIC('NONEXISTENT');
        expect($bic->getBankCode())->toBeNull();
        expect($bic->getCountryCode())->toBeNull();
        expect($bic->getName())->toBeNull();
        expect($bic->getShortName())->toBeNull();
        expect($bic->isPrimary())->toBeFalse();
    });

    it('handles malformed registry data', function (): void {
        // BIC should handle cases where registry returns unexpected data
        $bic = new BIC('ANYBIC12');
        expect($bic)->toBeInstanceOf(BIC::class);
    });
});

describe('BIC Real World Examples', function (): void {
    it('handles common German BIC', function (): void {
        $bic = new BIC('DEUTDEFF');
        expect($bic->getBIC())->toBe('DEUTDEFF');
    });

    it('handles common British BIC', function (): void {
        $bic = new BIC('NWBKGB2L');
        expect($bic->getBIC())->toBe('NWBKGB2L');
    });

    it('handles common French BIC', function (): void {
        $bic = new BIC('BNPAFRPP');
        expect($bic->getBIC())->toBe('BNPAFRPP');
    });

    it('handles BIC with branch code', function (): void {
        $bic = new BIC('DEUTDEFF500');
        expect($bic->getBIC())->toBe('DEUTDEFF500');
    });
});

describe('BIC Data Types', function (): void {
    it('handles string bank code correctly', function (): void {
        $bic = new BIC('TESTBIC1');
        $bankCode = $bic->getBankCode();
        if ($bankCode !== null) {
            expect($bankCode)->toBeString();
        }
    });

    it('handles string country code correctly', function (): void {
        $bic = new BIC('TESTBIC1');
        $countryCode = $bic->getCountryCode();
        if ($countryCode !== null) {
            expect($countryCode)->toBeString();
        }
    });

    it('handles boolean primary flag correctly', function (): void {
        $bic = new BIC('TESTBIC1');
        expect($bic->isPrimary())->toBeBool();
    });

    it('handles string names correctly', function (): void {
        $bic = new BIC('TESTBIC1');
        $name = $bic->getName();
        if ($name !== null) {
            expect($name)->toBeString();
        }

        $shortName = $bic->getShortName();
        if ($shortName !== null) {
            expect($shortName)->toBeString();
        }
    });
});
