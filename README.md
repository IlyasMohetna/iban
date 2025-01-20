<p align="center">
    <img src="https://raw.githubusercontent.com/ilyasmohetna/iban/main/docs/logo.svg" height="300" alt="PHP IBAN">
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

## Supported Countries

## Supported Countries

| Flag | Country                    | IBAN Validation | Auto Bank Detection |
| :--: | :------------------------- | :-------------: | :-----------------: |
|  🇦🇩  | Andorra                    |       ✅        |         ❌          |
|  🇦🇪  | United Arab Emirates (The) |       ✅        |         ❌          |
|  🇦🇱  | Albania                    |       ✅        |         ❌          |
|  🇦🇹  | Austria                    |       ✅        |         ❌          |
|  🇦🇿  | Azerbaijan                 |       ✅        |         ❌          |
|  🇧🇦  | Bosnia and Herzegovina     |       ✅        |         ❌          |
|  🇧🇪  | Belgium                    |       ✅        |         ❌          |
|  🇧🇬  | Bulgaria                   |       ✅        |         ❌          |
|  🇧🇭  | Bahrain                    |       ✅        |         ❌          |
|  🇧🇮  | Burundi                    |       ✅        |         ❌          |
|  🇧🇷  | Brazil                     |       ✅        |         ❌          |
|  🇧🇾  | Republic of Belarus        |       ✅        |         ❌          |
|  🇨🇭  | Switzerland                |       ✅        |         ❌          |
|  🇨🇷  | Costa Rica                 |       ✅        |         ❌          |
|  🇨🇾  | Cyprus                     |       ✅        |         ❌          |
|  🇨🇿  | Czechia                    |       ✅        |         ❌          |
|  🇩🇪  | Germany                    |       ✅        |         ❌          |
|  🇩🇯  | Djibouti                   |       ✅        |         ❌          |
|  🇩🇰  | Denmark                    |       ✅        |         ❌          |
|  🇩🇴  | Dominican Republic         |       ✅        |         ❌          |
|  🇪🇪  | Estonia                    |       ✅        |         ❌          |
|  🇪🇬  | Egypt                      |       ✅        |         ❌          |
|  🇪🇸  | Spain                      |       ✅        |         ❌          |
|  🇫🇮  | Finland                    |       ✅        |         ❌          |
|  🇫🇰  | Falkland Islands           |       ✅        |         ❌          |
|  🇫🇴  | Faroe Islands              |       ✅        |         ❌          |
|  🇫🇷  | France                     |       ✅        |         ❌          |
|  🇬🇧  | United Kingdom             |       ✅        |         ❌          |
|  🇬🇪  | Georgia                    |       ✅        |         ❌          |
|  🇬🇮  | Gibraltar                  |       ✅        |         ❌          |
|  🇬🇱  | Greenland                  |       ✅        |         ❌          |
|  🇬🇷  | Greece                     |       ✅        |         ❌          |
|  🇬🇹  | Guatemala                  |       ✅        |         ❌          |
|  🇭🇷  | Croatia                    |       ✅        |         ❌          |
|  🇭🇺  | Hungary                    |       ✅        |         ❌          |
|  🇮🇪  | Ireland                    |       ✅        |         ❌          |
|  🇮🇱  | Israel                     |       ✅        |         ❌          |
|  🇮🇶  | Iraq                       |       ✅        |         ❌          |
|  🇮🇸  | Iceland                    |       ✅        |         ❌          |
|  🇮🇹  | Italy                      |       ✅        |         ❌          |
|  🇯🇴  | Jordan                     |       ✅        |         ❌          |
|  🇰🇼  | Kuwait                     |       ✅        |         ❌          |
|  🇰🇿  | Kazakhstan                 |       ✅        |         ❌          |
|  🇱🇧  | Lebanon                    |       ✅        |         ❌          |
|  🇱🇨  | Saint Lucia                |       ✅        |         ❌          |
|  🇱🇮  | Liechtenstein              |       ✅        |         ❌          |
|  🇱🇹  | Lithuania                  |       ✅        |         ❌          |
|  🇱🇺  | Luxembourg                 |       ✅        |         ❌          |
|  🇱🇻  | Latvia                     |       ✅        |         ❌          |
|  🇱🇾  | Libya                      |       ✅        |         ❌          |
|  🇲🇨  | Monaco                     |       ✅        |         ❌          |
|  🇲🇩  | Moldova                    |       ✅        |         ❌          |
|  🇲🇪  | Montenegro                 |       ✅        |         ❌          |
|  🇲🇰  | Macedonia                  |       ✅        |         ❌          |
|  🇲🇳  | Mongolia                   |       ✅        |         ❌          |
|  🇲🇷  | Mauritania                 |       ✅        |         ❌          |
|  🇲🇹  | Malta                      |       ✅        |         ❌          |
|  🇲🇺  | Mauritius                  |       ✅        |         ❌          |
|  🇳🇮  | Nicaragua                  |       ✅        |         ❌          |
|  🇳🇱  | Netherlands (The)          |       ✅        |         ❌          |
|  🇳🇴  | Norway                     |       ✅        |         ❌          |
|  🇵🇰  | Pakistan                   |       ✅        |         ❌          |
|  🇴🇲  | Oman                       |       ✅        |         ❌          |
|  🇵🇱  | Poland                     |       ✅        |         ❌          |
|  🇶🇦  | Qatar                      |       ✅        |         ❌          |
|  🇷🇴  | Romania                    |       ✅        |         ❌          |
|  🇷🇸  | Serbia                     |       ✅        |         ❌          |
|  🇷🇺  | Russia                     |       ✅        |         ❌          |
|  🇸🇦  | Saudi Arabia               |       ✅        |         ❌          |
|  🇸🇨  | Seychelles                 |       ✅        |         ❌          |
|  🇸🇩  | Sudan                      |       ✅        |         ❌          |
|  🇸🇪  | Sweden                     |       ✅        |         ❌          |
|  🇸🇮  | Slovenia                   |       ✅        |         ❌          |
|  🇸🇰  | Slovakia                   |       ✅        |         ❌          |
|  🇸🇲  | San Marino                 |       ✅        |         ❌          |
|  🇸🇴  | Somalia                    |       ✅        |         ❌          |
|  🇸🇹  | Sao Tome and Principe      |       ✅        |         ❌          |
|  🇸🇻  | El Salvador                |       ✅        |         ❌          |
|  🇹🇱  | Timor-Leste                |       ✅        |         ❌          |
|  🇹🇳  | Tunisia                    |       ✅        |         ❌          |
|  🇹🇷  | Turkey                     |       ✅        |         ❌          |
|  🇺🇦  | Ukraine                    |       ✅        |         ❌          |
|  🇻🇦  | Vatican City State         |       ✅        |         ❌          |
|  🇻🇬  | Virgin Islands             |       ✅        |         ❌          |
|  🇽🇰  | Kosovo                     |       ✅        |         ❌          |

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
