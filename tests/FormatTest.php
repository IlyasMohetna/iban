<?php

namespace IlyasMohetna\Iban\Tests;

use IlyasMohetna\Iban\Enums\Format;
use IlyasMohetna\Iban\IBAN;

describe('Format Enum', function (): void {
    it('has all expected format cases', function (): void {
        expect(Format::PRINT)->toBeInstanceOf(Format::class);
        expect(Format::ELECTRONIC)->toBeInstanceOf(Format::class);
        expect(Format::ANONYMIZED)->toBeInstanceOf(Format::class);
    });

    it('format cases are distinct', function (): void {
        expect(Format::PRINT)->not()->toBe(Format::ELECTRONIC);
        expect(Format::PRINT)->not()->toBe(Format::ANONYMIZED);
        expect(Format::ELECTRONIC)->not()->toBe(Format::ANONYMIZED);
    });

    it('can be used in match statements', function (): void {
        $testFormat = Format::PRINT;

        $result = match ($testFormat) {
            Format::PRINT => 'print',
            Format::ELECTRONIC => 'electronic',
            Format::ANONYMIZED => 'anonymized',
        };

        expect($result)->toBe('print');
    });

    it('works with IBAN formatting', function (): void {
        $iban = new IBAN('DE89370400440532013000');

        // Test each format produces different results
        $printFormat = $iban->format(Format::PRINT);
        $electronicFormat = $iban->format(Format::ELECTRONIC);
        $anonymizedFormat = $iban->format(Format::ANONYMIZED);

        expect($printFormat)->not()->toBe($electronicFormat);
        expect($printFormat)->not()->toBe($anonymizedFormat);
        expect($electronicFormat)->not()->toBe($anonymizedFormat);
    });
});

describe('Format Integration with IBAN', function (): void {
    beforeEach(function (): void {
        $this->iban = new IBAN('DE89370400440532013000');
    });

    it('formats in PRINT format correctly', function (): void {
        $result = $this->iban->format(Format::PRINT);
        expect($result)->toBe('DE89 3704 0044 0532 0130 00');
        expect($result)->toContain(' '); // Should have spaces
    });

    it('formats in ELECTRONIC format correctly', function (): void {
        $result = $this->iban->format(Format::ELECTRONIC);
        expect($result)->toBe('DE89370400440532013000');
        expect($result)->not()->toContain(' '); // Should not have spaces
    });

    it('formats in ANONYMIZED format correctly', function (): void {
        $result = $this->iban->format(Format::ANONYMIZED);
        expect($result)->toBe('XXXXXXXXXXXXXXXXXX3000');
        expect($result)->toContain('X'); // Should have X's
        expect($result)->toEndWith('3000'); // Should end with last 4 digits
    });

    it('uses PRINT format as default', function (): void {
        $defaultFormat = $this->iban->format();
        $explicitPrint = $this->iban->format(Format::PRINT);
        expect($defaultFormat)->toBe($explicitPrint);
    });
});

describe('Format with Different IBAN Lengths', function (): void {
    it('works with short IBANs', function (): void {
        $iban = new IBAN('NO9386011117947'); // 15 characters

        $print = $iban->format(Format::PRINT);
        $electronic = $iban->format(Format::ELECTRONIC);
        $anonymized = $iban->format(Format::ANONYMIZED);

        expect($print)->toBe('NO93 8601 1117 947');
        expect($electronic)->toBe('NO9386011117947');
        expect($anonymized)->toBe('XXXXXXXXXXX7947');
    });

    it('works with long IBANs', function (): void {
        $iban = new IBAN('LC55HEMM000100010012001200023015'); // 32 characters

        $print = $iban->format(Format::PRINT);
        $electronic = $iban->format(Format::ELECTRONIC);
        $anonymized = $iban->format(Format::ANONYMIZED);

        expect($print)->toBe('LC55 HEMM 0001 0001 0012 0012 0002 3015');
        expect($electronic)->toBe('LC55HEMM000100010012001200023015');
        expect($anonymized)->toBe('XXXXXXXXXXXXXXXXXXXXXXXXXXXX3015');
    });

    it('works with medium length IBANs', function (): void {
        $iban = new IBAN('GB29NWBK60161331926819'); // 22 characters

        $print = $iban->format(Format::PRINT);
        $electronic = $iban->format(Format::ELECTRONIC);
        $anonymized = $iban->format(Format::ANONYMIZED);

        expect($print)->toBe('GB29 NWBK 6016 1331 9268 19');
        expect($electronic)->toBe('GB29NWBK60161331926819');
        expect($anonymized)->toBe('XXXXXXXXXXXXXXXXXX6819');
    });
});

describe('Format Edge Cases', function (): void {
    it('handles IBAN with letters in BBAN', function (): void {
        $iban = new IBAN('FR1420041010050500013M02606');

        $print = $iban->format(Format::PRINT);
        $electronic = $iban->format(Format::ELECTRONIC);
        $anonymized = $iban->format(Format::ANONYMIZED);

        expect($print)->toContain('M'); // Should preserve letters
        expect($electronic)->toContain('M');
        expect($anonymized)->toEndWith('2606'); // Should end with last 4 chars
    });

    it('maintains correct spacing in PRINT format', function (): void {
        $iban = new IBAN('IT60X0542811101000000123456');

        $print = $iban->format(Format::PRINT);
        $parts = explode(' ', $print);

        // Should be split into groups of 4 characters
        foreach ($parts as $part) {
            expect(strlen($part))->toBeLessThanOrEqual(4);
            expect(strlen($part))->toBeGreaterThan(0);
        }
    });

    it('preserves exact length in ELECTRONIC format', function (): void {
        $testIbans = [
            'NO9386011117947',
            'DE89370400440532013000',
            'LC55HEMM000100010012001200023015',
        ];

        foreach ($testIbans as $ibanString) {
            $iban = new IBAN($ibanString);
            $electronic = $iban->format(Format::ELECTRONIC);
            expect(strlen($electronic))->toBe(strlen($ibanString));
        }
    });

    it('anonymizes correctly regardless of IBAN content', function (): void {
        $testIbans = [
            'DE89370400440532013000',
            'FR1420041010050500013M02606',
            'IT60X0542811101000000123456',
        ];

        foreach ($testIbans as $ibanString) {
            $iban = new IBAN($ibanString);
            $anonymized = $iban->format(Format::ANONYMIZED);

            // Should end with last 4 characters
            expect($anonymized)->toEndWith(substr($ibanString, -4));

            // Should start with X's
            expect($anonymized)->toStartWith('X');

            // Should be same length as original
            expect(strlen($anonymized))->toBe(strlen($ibanString));
        }
    });
});

describe('Format Type Safety', function (): void {
    it('accepts only Format enum values', function (): void {
        $iban = new IBAN('DE89370400440532013000');

        // These should work
        expect($iban->format(Format::PRINT))->toBeString();
        expect($iban->format(Format::ELECTRONIC))->toBeString();
        expect($iban->format(Format::ANONYMIZED))->toBeString();
    });

    it('can be passed around as parameter', function (): void {
        $formatIban = fn (IBAN $iban, Format $format): string => $iban->format($format);

        $iban = new IBAN('DE89370400440532013000');

        expect($formatIban($iban, Format::PRINT))->toBeString();
        expect($formatIban($iban, Format::ELECTRONIC))->toBeString();
        expect($formatIban($iban, Format::ANONYMIZED))->toBeString();
    });

    it('can be stored in arrays', function (): void {
        $formats = [Format::PRINT, Format::ELECTRONIC, Format::ANONYMIZED];
        $iban = new IBAN('DE89370400440532013000');

        foreach ($formats as $format) {
            expect($iban->format($format))->toBeString();
        }
    });
});
