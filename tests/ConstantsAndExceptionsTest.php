<?php

namespace IlyasMohetna\Iban\Tests;

use Exception;
use IlyasMohetna\Iban\Constants\IBANConstants;
use IlyasMohetna\Iban\Exceptions\InvalidBICException;
use IlyasMohetna\Iban\Exceptions\InvalidIBANException;
use IlyasMohetna\Iban\Exceptions\UnsupportedCountryCodeException;

describe('IBANConstants', function (): void {
    it('has correct country code constants', function (): void {
        expect(IBANConstants::COUNTRY_CODE_OFFSET)->toBe(0);
        expect(IBANConstants::COUNTRY_CODE_LENGTH)->toBe(2);
    });

    it('has correct checksum constants', function (): void {
        expect(IBANConstants::CHECKSUM_OFFSET)->toBe(2);
        expect(IBANConstants::CHECKSUM_LENGTH)->toBe(2);
    });

    it('has correct BBAN offset', function (): void {
        expect(IBANConstants::BBAN_OFFSET)->toBe(4);
    });

    it('has correct length constraints', function (): void {
        expect(IBANConstants::MAX_IBAN_LENGTH)->toBe(34);
        expect(IBANConstants::MIN_IBAN_LENGTH)->toBe(15);
        expect(IBANConstants::MAX_IBAN_LENGTH)->toBeGreaterThan(IBANConstants::MIN_IBAN_LENGTH);
    });

    it('has correct validation constants', function (): void {
        expect(IBANConstants::MODULO_97)->toBe('97');
        expect(IBANConstants::VALID_CHECKSUM_REMAINDER)->toBe('1');
    });

    it('has correct character conversion constant', function (): void {
        expect(IBANConstants::ALPHA_TO_NUMBER_OFFSET)->toBe(55);

        // Verify the conversion logic works correctly
        // 'A' = ASCII 65, should convert to 10
        expect(ord('A') - IBANConstants::ALPHA_TO_NUMBER_OFFSET)->toBe(10);
        // 'Z' = ASCII 90, should convert to 35
        expect(ord('Z') - IBANConstants::ALPHA_TO_NUMBER_OFFSET)->toBe(35);
    });

    it('cannot be instantiated', function (): void {
        $reflection = new \ReflectionClass(IBANConstants::class);
        $constructor = $reflection->getConstructor();
        expect($constructor->isPrivate())->toBeTrue();
    });

    it('has all constants as public', function (): void {
        $reflection = new \ReflectionClass(IBANConstants::class);
        $constants = $reflection->getConstants();

        expect($constants)->toHaveKey('COUNTRY_CODE_OFFSET');
        expect($constants)->toHaveKey('COUNTRY_CODE_LENGTH');
        expect($constants)->toHaveKey('CHECKSUM_OFFSET');
        expect($constants)->toHaveKey('CHECKSUM_LENGTH');
        expect($constants)->toHaveKey('BBAN_OFFSET');
        expect($constants)->toHaveKey('MAX_IBAN_LENGTH');
        expect($constants)->toHaveKey('MIN_IBAN_LENGTH');
        expect($constants)->toHaveKey('MODULO_97');
        expect($constants)->toHaveKey('VALID_CHECKSUM_REMAINDER');
        expect($constants)->toHaveKey('ALPHA_TO_NUMBER_OFFSET');
    });

    it('has correct data types for constants', function (): void {
        expect(IBANConstants::COUNTRY_CODE_OFFSET)->toBeInt();
        expect(IBANConstants::COUNTRY_CODE_LENGTH)->toBeInt();
        expect(IBANConstants::CHECKSUM_OFFSET)->toBeInt();
        expect(IBANConstants::CHECKSUM_LENGTH)->toBeInt();
        expect(IBANConstants::BBAN_OFFSET)->toBeInt();
        expect(IBANConstants::MAX_IBAN_LENGTH)->toBeInt();
        expect(IBANConstants::MIN_IBAN_LENGTH)->toBeInt();
        expect(IBANConstants::MODULO_97)->toBeString();
        expect(IBANConstants::VALID_CHECKSUM_REMAINDER)->toBeString();
        expect(IBANConstants::ALPHA_TO_NUMBER_OFFSET)->toBeInt();
    });

    it('validates logical relationships between constants', function (): void {
        // Checksum should come after country code
        expect(IBANConstants::CHECKSUM_OFFSET)->toBeGreaterThan(IBANConstants::COUNTRY_CODE_OFFSET);

        // BBAN should come after checksum
        expect(IBANConstants::BBAN_OFFSET)->toBeGreaterThan(IBANConstants::CHECKSUM_OFFSET);

        // BBAN offset should equal country code length + checksum length
        expect(IBANConstants::BBAN_OFFSET)->toBe(
            IBANConstants::COUNTRY_CODE_LENGTH + IBANConstants::CHECKSUM_LENGTH
        );
    });
});

describe('InvalidIBANException', function (): void {
    it('can be instantiated with a message', function (): void {
        $exception = new InvalidIBANException('Test message');
        expect($exception)->toBeInstanceOf(InvalidIBANException::class);
        expect($exception)->toBeInstanceOf(Exception::class);
    });

    it('stores the message correctly', function (): void {
        $message = 'Invalid IBAN format';
        $exception = new InvalidIBANException($message);
        expect($exception->getMessage())->toBe($message);
    });

    it('can be thrown and caught', function (): void {
        expect(function (): void {
            throw new InvalidIBANException('Test exception');
        })->toThrow(InvalidIBANException::class);
    });

    it('can be caught as general Exception', function (): void {
        expect(function (): void {
            throw new InvalidIBANException('Test exception');
        })->toThrow(Exception::class);
    });

    it('handles empty message', function (): void {
        $exception = new InvalidIBANException('');
        expect($exception->getMessage())->toBe('');
    });

    it('handles special characters in message', function (): void {
        $message = 'Invalid IBAN: DE89-3704@0044#0532$0130%00';
        $exception = new InvalidIBANException($message);
        expect($exception->getMessage())->toBe($message);
    });

    it('handles Unicode characters in message', function (): void {
        $message = 'Invalid IBAN: Ungültige IBAN-Nummer';
        $exception = new InvalidIBANException($message);
        expect($exception->getMessage())->toBe($message);
    });
});

describe('UnsupportedCountryCodeException', function (): void {
    it('can be instantiated with a message', function (): void {
        $exception = new UnsupportedCountryCodeException('Test message');
        expect($exception)->toBeInstanceOf(UnsupportedCountryCodeException::class);
        expect($exception)->toBeInstanceOf(Exception::class);
    });

    it('stores the message correctly', function (): void {
        $message = 'Country code XX is not supported';
        $exception = new UnsupportedCountryCodeException($message);
        expect($exception->getMessage())->toBe($message);
    });

    it('can be thrown and caught', function (): void {
        expect(function (): void {
            throw new UnsupportedCountryCodeException('Test exception');
        })->toThrow(UnsupportedCountryCodeException::class);
    });

    it('can be caught as general Exception', function (): void {
        expect(function (): void {
            throw new UnsupportedCountryCodeException('Test exception');
        })->toThrow(Exception::class);
    });

    it('handles country code in message', function (): void {
        $message = "Country code 'XY' is not supported.";
        $exception = new UnsupportedCountryCodeException($message);
        expect($exception->getMessage())->toBe($message);
    });
});

describe('InvalidBICException', function (): void {
    it('can be instantiated with a message', function (): void {
        $exception = new InvalidBICException('Test message');
        expect($exception)->toBeInstanceOf(InvalidBICException::class);
        expect($exception)->toBeInstanceOf(Exception::class);
    });

    it('stores the message correctly', function (): void {
        $message = 'Invalid BIC format';
        $exception = new InvalidBICException($message);
        expect($exception->getMessage())->toBe($message);
    });

    it('can be thrown and caught', function (): void {
        expect(function (): void {
            throw new InvalidBICException('Test exception');
        })->toThrow(InvalidBICException::class);
    });

    it('can be caught as general Exception', function (): void {
        expect(function (): void {
            throw new InvalidBICException('Test exception');
        })->toThrow(Exception::class);
    });

    it('handles BIC code in message', function (): void {
        $message = "BIC 'INVALIDBIC' has incorrect format.";
        $exception = new InvalidBICException($message);
        expect($exception->getMessage())->toBe($message);
    });
});

describe('Exception Hierarchy', function (): void {
    it('all custom exceptions extend base Exception', function (): void {
        $invalidIban = new InvalidIBANException('test');
        $unsupportedCountry = new UnsupportedCountryCodeException('test');
        $invalidBic = new InvalidBICException('test');

        expect($invalidIban)->toBeInstanceOf(Exception::class);
        expect($unsupportedCountry)->toBeInstanceOf(Exception::class);
        expect($invalidBic)->toBeInstanceOf(Exception::class);
    });

    it('exceptions are distinct types', function (): void {
        $invalidIban = new InvalidIBANException('test');
        $unsupportedCountry = new UnsupportedCountryCodeException('test');
        $invalidBic = new InvalidBICException('test');

        expect($invalidIban)->not()->toBeInstanceOf(UnsupportedCountryCodeException::class);
        expect($invalidIban)->not()->toBeInstanceOf(InvalidBICException::class);
        expect($unsupportedCountry)->not()->toBeInstanceOf(InvalidIBANException::class);
        expect($unsupportedCountry)->not()->toBeInstanceOf(InvalidBICException::class);
        expect($invalidBic)->not()->toBeInstanceOf(InvalidIBANException::class);
        expect($invalidBic)->not()->toBeInstanceOf(UnsupportedCountryCodeException::class);
    });

    it('can catch specific exception types', function (): void {
        try {
            throw new UnsupportedCountryCodeException('test');
        } catch (UnsupportedCountryCodeException $e) {
            expect($e->getMessage())->toBe('test');
        } catch (Exception) {
            throw new \RuntimeException('Should have caught UnsupportedCountryCodeException');
        }
    });
});

describe('Exception Usage in Real Scenarios', function (): void {
    it('handles typical IBAN validation error messages', function (): void {
        $messages = [
            "The IBAN 'DE88370400440532013000' has an invalid checksum.",
            "The IBAN 'GB29NWBK6016133192681' has an incorrect length for country 'GB'.",
            "The IBAN 'FR14' cannot be normalized.",
            'Country code is missing.',
        ];

        foreach ($messages as $message) {
            $exception = new InvalidIBANException($message);
            expect($exception->getMessage())->toBe($message);
        }
    });

    it('handles typical country code error messages', function (): void {
        $messages = [
            "Country code 'XX' is not supported.",
            "Country code 'ZZ' is not supported.",
            "Missing key 'iban_regex' for country 'YY' in IBAN registry.",
        ];

        foreach ($messages as $message) {
            $exception = new UnsupportedCountryCodeException($message);
            expect($exception->getMessage())->toBe($message);
        }
    });

    it('handles typical BIC error messages', function (): void {
        $messages = [
            "BIC 'INVALID' has incorrect format.",
            "BIC 'TOOLONGBICCODE123' exceeds maximum length.",
            "BIC '' cannot be empty.",
        ];

        foreach ($messages as $message) {
            $exception = new InvalidBICException($message);
            expect($exception->getMessage())->toBe($message);
        }
    });
});
