<?php

namespace IlyasMohetna\Iban\Tests;

use IlyasMohetna\Iban\IBAN;

it('can be instantiated', function (): void {
    $iban = new IBAN('FR1111111111111111111111111');
    expect($iban)->toBeInstanceOf(IBAN::class);
});

it('can be cast to a string', function (): void {
    $iban = new IBAN('FR1111111111111111111111111');
    expect((string) $iban->getIban())->toBe('FR1111111111111111111111111');
});

it('can be validated', function (): void {
    $iban = new IBAN('FR1111111111111111111111111');
    expect($iban->isValid())->toBeFalse();
});
