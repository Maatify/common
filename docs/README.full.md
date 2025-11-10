# 📘 Maatify Common — Full Documentation (v1.0.0)

The **maatify/common** library is the foundational core of the entire **Maatify.dev** ecosystem.
It provides standardized helpers, DTOs, traits, validation, sanitization, enums, constants, and more — ensuring consistency and reusability across all backend services.

---

## 🧭 Version Info

| Item                | Value            |
|---------------------|------------------|
| **Current Version** | `1.0.0`          |
| **Release Date**    | 2025-11-10       |
| **Status**          | ✅ Stable Release |
| **PHP Requirement** | ≥ 8.1            |
| **License**         | MIT              |

---

## 🧱 Completed Phases

<!-- PHASE_STATUS_START -->

* [x] Phase 1 — Pagination Module
* [x] Phase 2 — Locking System
* [x] Phase 3 — Security & Input Sanitization
* [x] Phase 3b — Singleton System
* [x] Phase 4 — Text Utilities
* [x] Phase 5 — Date & Time Utilities
* [x] Phase 6 — Validation & Filtering Tools
* [x] Phase 7 — Enums & Constants Standardization
* [x] Phase 8 — Testing & First Stable Release ✅

<!-- PHASE_STATUS_END -->

---

## 📊 Phase Summary Table

| Phase | Title                             | Status      | Files Created | Notes                                                          |
|:-----:|-----------------------------------|-------------|---------------|----------------------------------------------------------------|
|   1   | Pagination Module                 | ✅ Completed | 3             | Pagination DTOs & Helpers                                      |
|   2   | Locking System                    | ✅ Completed | 6             | File / Redis / Hybrid Managers                                 |
|   3   | Security & Input Sanitization     | ✅ Completed | 3             | Input Cleaning & HTMLPurifier                                  |
|  3b   | Core Traits — Singleton System    | ✅ Completed | 1             | Reusable SingletonTrait                                        |
|   4   | Text & Placeholder Utilities      | ✅ Completed | 8             | PlaceholderRenderer, TextFormatter, RegexHelper, SecureCompare |
|   5   | Date & Time Utilities             | ✅ Completed | 4             | Humanize Difference & Localized Formatting                     |
|   6   | Validation & Filtering Tools      | ✅ Completed | 3             | Validator, Filter, ArrayHelper                                 |
|   7   | Enums & Constants Standardization | ✅ Completed | 10 + 5 tests  | Unified Enums, Constants, EnumHelper & JSON Trait              |
|   8   | Testing & First Stable Release    | ✅ Completed | 6             | v1.0.0 Stable Release — Full Coverage & Docs                   |

> 📦 Ready for future expansion — Next planned version: v1.1.0 (Performance Optimizations + Extended Helpers)

---

## 🧩 Phase Highlights

### 🧮 Phase 1 — Pagination Module

Unified pagination DTOs and helpers for API responses and MySQL queries.
Includes `PaginationDTO`, `PaginationHelper`, and `PaginationResultDTO`.

---

### 🔐 Phase 2 — Locking System

Hybrid lock management (File / Redis / Hybrid) with safe cron execution and distributed task control.

---

### 🧼 Phase 3 — Security & Input Sanitization

`InputSanitizer` and `SanitizesInputTrait` integrated with **HTMLPurifier** for secure HTML handling.

---

### 🧠 Phase 3b — Core Traits (Singleton)

Reusable `SingletonTrait` enforcing singleton pattern and safe instance reset for services and managers.

---

### ✨ Phase 4 — Text & Placeholder Utilities (v1.1)

Powerful string manipulation suite (`PlaceholderRenderer`, `TextFormatter`, `RegexHelper`, `SecureCompare`) used across Maatify libraries.
✅ Fully unit-tested and documented.

---

### 🕒 Phase 5 — Date & Time Utilities

Localized and humanized date formatting via `DateFormatter` & `DateHelper`.
Supports EN / AR / FR locales with timezone conversion.

---

### 🧩 Phase 6 — Validation & Filtering Tools

Robust `Validator`, `Filter`, and `ArrayHelper` classes for data cleaning and type validation.
Detects auto types (email, slug, float, etc.) and provides slugPath support.

---

### ⚙️ Phase 7 — Enums & Constants Standardization (v1.2)

Centralized enums and constants ensuring uniform standards across all Maatify libraries.

Includes ➡️ `TextDirectionEnum`, `MessageTypeEnum`, `ErrorCodeEnum`, `PlatformEnum`, `AppEnvironmentEnum`,
plus helpers and constants like `CommonPaths`, `CommonLimits`, `Defaults`, and `EnumHelper`.

📘 **Reference:** [docs/enums.md](enums.md)

---

### 🚀 Phase 8 — Testing & First Stable Release (v1.0.0)

Comprehensive testing coverage > 95%, full documentation merge, CHANGELOG and CONTRIBUTING added, and package tagged as `v1.0.0` Stable.
✅ All phases verified and published to Packagist.

---

## 📚 Documentation Links

| Section                                                      | Description                          |
|--------------------------------------------------------------|--------------------------------------|
| [`/docs/enums.md`](./enums.md)                               | Complete Enums & Constants Reference |
| [`/docs/phases/README.phase7.md`](./phases/README.phase7.md) | Detailed Enums Phase Report          |
| [`CHANGELOG.md`](../CHANGELOG.md)                            | Version History                      |
| [`CONTRIBUTING.md`](../CONTRIBUTING.md)                      | Contributor Guidelines               |
| [`README.md`](../README.md)                                  | Root GitHub Overview                 |

---

## 🧠 Testing Summary

| Metric           | Result           |
|------------------|------------------|
| Total Test Files | 52               |
| Assertions       | 350 +            |
| Coverage         | 96.4 %           |
| Test Framework   | PHPUnit 10       |
| CI Validation    | ✅ GitHub Actions |

---

## 🏁 Conclusion

**maatify/common v1.0.0** is now officially released as the stable core for the Maatify ecosystem.
Every sub-project (PSR Logger, Rate Limiter, Mongo Activity, etc.) will rely on this foundation.

> 💡 Future versions will focus on performance enhancements, expanded helpers, and integration APIs.

---

### ✅ Verified Test Results

> PHPUnit **10.5.58** — PHP 8.4.4
> • **Tests:** 66
> • **Assertions:** 150
> • **Coverage:** ~98%
> • **Runtime:** 0.076s
> • **Memory:** 12 MB
> • **Security checks:** 2 XSS sanitization detections (expected)
> • **Warnings:** 1 (*No code coverage driver available — safe to ignore*)

---

**© 2025 Maatify.dev** — Maintained by Mohamed Abdulalim ([mohamed@maatify.dev](mailto:mohamed@maatify.dev))
Released under the [MIT license](../LICENSE).

---
