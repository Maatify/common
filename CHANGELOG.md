# 🧾 CHANGELOG

All notable changes to **maatify/common** will be documented in this file.
This project follows [Semantic Versioning](https://semver.org/).

## [1.0.3] – 2025-11-13

### 🧩 Connectivity Foundation — Introducing `ConnectionConfigDTO` & `ConnectionTypeEnum`

**Release Date:** 2025-11-13
**Author:** [Mohamed Abdulalim (megyptm)](mailto:mohamed@maatify.dev)
**License:** MIT
**Organization:** [Maatify.dev](https://www.maatify.dev)

---

### ⚙️ Overview

This update introduces two new standardized components that will be used across all Maatify backend libraries:

* **`ConnectionConfigDTO`** — a readonly DTO that encapsulates driver-specific connection settings.
* **`ConnectionTypeEnum`** — a unified enum for all supported connection types (`mysql`, `mongo`, `redis`).

These additions support the new DSN-based workflow planned in `maatify/data-adapters` (Phase 10)
and enforce consistency across the Maatify ecosystem.

> “One connection model — shared across all libraries.”

---

### 🧩 Added

#### ✔ `Maatify\Common\DTO\ConnectionConfigDTO`

* Readonly object representing:

    * `driver`
    * `dsn`
    * `user`
    * `pass`
    * `options`
    * `profile` (supports multiple DSN profiles in future releases)
* Basis for profile-based DSN resolution in data-adapters.

#### ✔ `Maatify\Common\Enums\ConnectionTypeEnum`

* Defines consistent adapter identifiers:

    * `MYSQL`
    * `MONGO`
    * `REDIS`

#### ✔ New Tests

* Added test suite:

    * `tests/DTO/ConnectionConfigDTOTest.php`
    * `tests/Enums/ConnectionTypeEnumTest.php`
* Verified:

    * DTO immutability
    * Enum integrity
    * PSR-12 compliance

#### ✔ Documentation Update

* Updated `/docs/core/helpers.md`
  → Added new section: **Connectivity Foundation**
* Linked from main README under **Core Modules**

---

### 🧱 Architectural Impact

* Establishes a **shared foundation** for database configurations.
* Required for **Phase 10** in `maatify/data-adapters` (string-based driver selection + DSN builder).
* Ensures **cross-library consistency** for all future adapters and DI containers.

---

### 🧾 Changelog Snapshot

**v1.0.3 — 2025-11-13**

* Added: `ConnectionConfigDTO`
* Added: `ConnectionTypeEnum`
* Added: Tests for DTO & Enum
* Updated: Docs with new Connectivity Foundation section
* No breaking changes — Fully backward compatible

---

## [1.0.2] – 2025-11-10

### ✨ Helper Utilities Expansion — Introducing `TapHelper`

**Release Date:** 2025-11-10
**Author:** [Mohamed Abdulalim (megyptm)](mailto:mohamed@maatify.dev)
**License:** MIT
**Organization:** [Maatify.dev](https://www.maatify.dev)

---

### ⚙️ Overview

This release introduces **`TapHelper`**, a new functional-style helper utility
that simplifies object initialization and enhances code fluency across all Maatify libraries.

It allows developers to execute a callback on any object or value and return that same value unchanged —
making adapter or service setup more expressive, readable, and concise.

> “Cleaner initialization, consistent patterns, zero boilerplate.”

---

### 🧩 Added

* **New Helper:** `Maatify\Common\Helpers\TapHelper`

    * Provides a static `tap()` method to execute a callback on any object or value.
    * Returns the same instance unchanged.
    * Fully PSR-12 compliant and functional in style.

* **Unit Tests:**

    * Added `tests/Helpers/TapHelperTest.php` to verify:

        * Object reference equality and immutability.
        * Proper callback execution.
        * Scalar and array handling.

* **Documentation:**

    * Updated `README.md`:

        * Added a new **🧩 Helper Utilities** section.
        * Included example usage, functional philosophy, and architectural benefits.
        * Linked reference from the **Core Modules** section.

---

### 🧱 Architectural Impact

* Promotes **fluent initialization** patterns across all Maatify libraries (`bootstrap`, `data-adapters`, `rate-limiter`, `redis-cache`, etc.).
* Enhances developer ergonomics and readability during service setup.
* Ensures **ecosystem-wide consistency** with other helpers (`PathHelper`, `EnumHelper`, `TimeHelper`).
* Backward compatible with v1.0.1 — no breaking changes introduced.

---

### 🧾 Changelog Snapshot

**v1.0.3 — 2025-11-10**

* Added: `TapHelper` utility under `Maatify\Common\Helpers`.
* Added: Full PHPUnit test coverage for `TapHelper`.
* Updated: `README.md` with new Helper Utilities documentation section.
* Improved: Developer experience for fluent adapter and service initialization.

---

## [1.0.1] – 2025-11-10

### 🧷 Maintenance & Logger Stability Update

**Release Date:** 2025-11-10
**Author:** [Mohamed Abdulalim (megyptm)](mailto:mohamed@maatify.dev)
**License:** MIT
**Organization:** [Maatify.dev](https://www.maatify.dev)

---

### ⚙️ Overview

This maintenance release improves internal logging reliability within the **HybridLockManager**.
A PSR-3 compliant fallback logger is now automatically initialized to prevent `TypeError` exceptions
when no logger instance is injected.

> “Resilient by design — no silent logs, no nulls.”

---

### ✨ Highlights

* **Fixed:** Added PSR-3 `LoggerInterface` fallback in `HybridLockManager`.
* **Improved:** Unified logger initialization using `LoggerContextTrait`.
* **Verified:** All lock-related tests pass on PHP 8.4.4 (98% coverage).
* **Maintained:** Fully backward-compatible with v1.0.0 — no breaking changes.

---

### 🧾 Changelog Snapshot

**v1.0.1 — 2025-11-10**

* Fixed: Logger fallback initialization in `HybridLockManager`.
* Improved: Logging consistency between Redis and File lock drivers.
* Verified: Full test suite passing under PHP 8.4.4.

---

## [1.0.0] – 2025-11-10

### 🎉 First Stable Release

This marks the first official stable release of the **Maatify Common Library**,
serving as the foundation for all other Maatify components.

---

### 🧩 Added

* **Pagination Module** — unified DTOs and helpers for paginated API results.
* **Locking System** — file-based, Redis, and hybrid lock managers for safe task execution.
* **Security & Input Sanitization** — universal sanitization and HTMLPurifier integration.
* **Core Traits** — singleton pattern and input sanitization traits.
* **Text & Placeholder Utilities** — placeholder rendering, text formatting, regex helpers, and secure comparison tools.
* **Date & Time Utilities** — humanized differences, timezone conversions, and locale-aware date rendering.
* **Validation & Filtering Tools** — robust validator, filter, and array helper for clean data handling.
* **Enums & Constants Standardization** — centralized enums, constants, EnumHelper, and JSON serialization trait.
* **Documentation** — detailed Markdown docs for all modules under `/docs/`.
* **Unit Tests** — comprehensive PHPUnit coverage (>95%) across all components.

---

### ⚙️ Internal

* Full **PSR-12** compliance and strict typing (`declare(strict_types=1);`).
* Integrated CI workflow for GitHub Actions (`tests.yml`).
* Composer autoload and version tracking finalized.

---

### 🧠 Notes

This release establishes the **maatify/common** library as the central core dependency
for all future Maatify modules such as `maatify/psr-logger`, `maatify/rate-limiter`, and `maatify/mongo-activity`.

> 📦 Next planned version: **v1.1.0** — introducing performance optimizations and additional helper utilities.

---

**© 2025 Maatify.dev** — Engineered by [Mohamed Abdulalim](https://www.maatify.dev)
Distributed under the [MIT License](LICENSE)

---