<p align="center">
    <img src="https://raw.githubusercontent.com/ilyasmohetna/iban/main/docs/logo.png" height="300" alt="PHP IBAN">
    <p align="center">
        <a href="https://github.com/ilyasmohetna/iban/actions"><img alt="GitHub Workflow Status (main)" src="https://github.com/ilyasmohetna/iban/actions/workflows/tests.yml/badge.svg"></a>
        <a href="https://packagist.org/packages/ilyasmohetna/iban"><img alt="Total Downloads" src="https://img.shields.io/packagist/dt/ilyasmohetna/iban"></a>
        <a href="https://packagist.org/packages/ilyasmohetna/iban"><img alt="Latest Version" src="https://img.shields.io/packagist/v/ilyasmohetna/iban"></a>
        <a href="https://packagist.org/packages/ilyasmohetna/iban"><img alt="License" src="https://img.shields.io/packagist/l/ilyasmohetna/iban"></a>
    </p>
</p>

---

This package provides a powerful and easy-to-use **PHP IBAN** utility for parsing, validating, and extracting bank details from IBANs.

> **Requires [PHP 8.0+](https://php.net/releases/)**

## Installation

Install the package using [Composer](https://getcomposer.org):

```bash
composer require ilyasmohetna/iban
```

## Features

-   ✅ Parse IBANs into structured components.
-   ✅ Validate IBAN structure and checksum.
-   ✅ Auto-detect bank details from IBANs.

## Usage

### Validate an IBAN

### Parse an IBAN

## Development

### Run Code Quality Tools

🧹 Keep a modern codebase with **Pint**:

```bash
composer lint
```

✅ Run refactors using **Rector**:

```bash
composer refacto
```

⚗️ Run static analysis using **PHPStan**:

```bash
composer test:types
```

✅ Run unit tests using **PEST**:

```bash
composer test:unit
```

🚀 Run the entire test suite:

```bash
composer test
```

---

**PHP IBAN** was created by **[Ilyas Mohetna](https://github.com/ilyasmohetna)** under the **[MIT license](https://opensource.org/licenses/MIT)**.
