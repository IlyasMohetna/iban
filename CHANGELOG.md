# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased]

-   Adds first version

# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased]

## [1.0.4] - 2025-01-24

### Added

-   `IBANRegistryInterface` for dependency injection and improved testability
-   `BICRegistryInterface` for dependency injection and improved testability

### Changed

-   `IBANRegistry` now implements `IBANRegistryInterface`
-   `BICRegistry` now implements `BICRegistryInterface`
-   `IBAN` class constructor now accepts `IBANRegistryInterface` instead of concrete implementation
-   Improved adherence to Dependency Inversion Principle (SOLID)

### Fixed

-   Dependency Inversion Principle violations in registry dependencies

## [1.0.3] - 2025-01-24

### Added

-   `IBANConstants` class with centralized constants for IBAN processing
-   Constants for IBAN structure offsets, lengths, and validation values

### Changed

-   Replaced hardcoded magic numbers in `IBAN` class with named constants
-   Improved code maintainability and readability

### Removed

-   Magic numbers and hardcoded values from IBAN validation logic

## [1.0.2] - Initial release

-   Adds first version
