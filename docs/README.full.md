# 🧩 Maatify Common — Full Documentation

**Core Foundation Library for the Maatify Ecosystem**

This document provides the **full combined technical documentation** for all phases implemented in `maatify/common`, including architecture, modules, utilities, enums, validation, sanitization, date helpers, text processing, and connectivity layers.

Each development phase is documented separately and linked below.

---

# 📑 Table of Contents

1. [Introduction](#introduction)
2. [Core Modules](#core-modules)
3. [System Design & Architecture](#system-design--architecture)
4. [Full Phase Documentation](#full-phase-documentation)

    * [Phase 1 — Pagination Module](./phases/README.phase1.md)
    * [Phase 2 — Locking System](./phases/README.phase2.md)
    * [Phase 3 — Security & Input Sanitization](./phases/README.phase3.md)
    * [Phase 3B — Singleton System](./phases/README.phase3b.md)
    * [Phase 4 — Text & Placeholder Utilities](./phases/README.phase4.md)
    * [Phase 5 — Date & Time Utilities](./phases/README.phase5.md)
    * [Phase 6 — Validation & Filtering Tools](./phases/README.phase6.md)
    * [Phase 7 — Enums & Constants Standardization](./phases/README.phase7.md)
    * [Phase 8 — Testing & Stable Release](./phases/README.phase8.md)
    * [Phase 9 — Logger Stability Update](./phases/README.phase9.md)
    * [Phase 10 — TapHelper Utility](./phases/README.phase10.md)
    * [Phase 11 — Connectivity Foundation](./phases/README.phase11.md)
    * [Phase 12 — VERSION File Fix](./phases/README.phase12.md)
    * [Phase 13 — Mutable ConnectionConfigDTO](./phases/README.phase13.md)
    * [Phase 14 — Driver Contract Modernization](./phases/README.phase14.md)
    * [**Phase 15 — Redis Lock Testing Stability Update** *(new)*](./phases/README.phase15.md)
5. [Directory Structure](#directory-structure)
6. [Testing & Coverage](#testing--coverage)
7. [Release Notes](#release-notes)
8. [License](#license)

---

# 🧭 Introduction

`maatify/common` is the **core foundational library** of the entire Maatify backend ecosystem.
It provides:

* standardized DTOs
* functional and text utilities
* sanitization and validation
* date/time localization
* enums & constants
* connection DTOs
* locking mechanisms
* helper abstractions used by all other Maatify packages

This library guarantees **consistent behavior**, **predictable patterns**, and **secure, reusable tools** for all backend services.

---

# 🧩 Core Modules

### ✔ Pagination Module
Unified DTOs & helpers for API and repository pagination.

### ✔ Locking System

Distributed, hybrid, Redis, and file-based mutex operations
→ **Phase 15 introduces realistic Redis TTL simulation for queue-mode tests**

### ✔ Security & Sanitization
XSS-safe sanitization powered by HTMLPurifier + mixed-type cleaning.

### ✔ Traits & Core Patterns
Reusable SingletonTrait and sanitization traits.

### ✔ Text Utilities
Placeholder rendering, slug normalization, regex tools, secure compare.

### ✔ Date & Time Helpers
Localized formatting (EN/AR/FR), timezone conversion, humanized differences.

### ✔ Validation & Filtering
Email/URL/UUID/Slug validation + array cleanup + type detection.

### ✔ Enums & Constants
Global standard enums for all Maatify components.

### ✔ Connectivity Foundation
Standardized configuration for MySQL/Mongo/Redis drivers.

### ✔ Helper Utilities
TapHelper for fluent initialization and functional pipelines.

---

# 🏗 System Design & Architecture

This library sits at the **root layer** of the Maatify ecosystem.

```
┌──────────────────────────────┐
│         maatify/common       │  ← Core Level
└───────────────┬──────────────┘
                │
                ▼
        Shared Infrastructure
        - data-adapters
        - psr-logger
        - security-guard
        - i18n / localization
        - repository layer
        - messaging-core
                │
                ▼
        Application Services
        - ecommerce
        - dashboards
        - otp systems
        - admin portals
                │
                ▼
       End-user Applications
```

Every higher module depends on this library for consistency and standardization.

---

# 📚 Full Phase Documentation

Updated table now includes Phase 15:

| Phase        | Description                             | Link                                            |
|--------------|-----------------------------------------|-------------------------------------------------|
| Phase 1      | Pagination Module                       | [README.phase1.md](./phases/README.phase1.md)   |
| Phase 2      | Locking System                          | [README.phase2.md](./phases/README.phase2.md)   |
| Phase 3      | Input Sanitization                      | [README.phase3.md](./phases/README.phase3.md)   |
| Phase 3B     | Singleton System                        | [README.phase3b.md](./phases/README.phase3b.md) |
| Phase 4      | Text Utilities                          | [README.phase4.md](./phases/README.phase4.md)   |
| Phase 5      | Date Utilities                          | [README.phase5.md](./phases/README.phase5.md)   |
| Phase 6      | Validation & Filtering                  | [README.phase6.md](./phases/README.phase6.md)   |
| Phase 7      | Enums & Constants                       | [README.phase7.md](./phases/README.phase7.md)   |
| Phase 8      | Testing & Release                       | [README.phase8.md](./phases/README.phase8.md)   |
| Phase 9      | Logger Stability Update                 | [README.phase9.md](./phases/README.phase9.md)   |
| Phase 10     | TapHelper Utility                       | [README.phase10.md](./phases/README.phase10.md) |
| Phase 11     | Connectivity Foundation                 | [README.phase11.md](./phases/README.phase11.md) |
| Phase 12     | VERSION File Fix                        | [README.phase12.md](./phases/README.phase12.md) |
| Phase 13     | Mutable ConnectionConfigDTO             | [README.phase13.md](./phases/README.phase13.md) |
| Phase 14     | Driver Contract Modernization           | [README.phase14.md](./phases/README.phase14.md) |
| **Phase 15** | **Redis Lock Testing Stability Update** | [README.phase15.md](./phases/README.phase15.md) |

---

# 🗂 Directory Structure

```
src/
├── Pagination/
├── Lock/
├── Security/
├── Traits/
├── Text/
├── Date/
├── Validation/
├── DTO/
├── Enums/
└── Constants/

tests/
└── (...) complete test suite
```

---

# 🧪 Testing & Coverage

Updated testing section now includes Phase 15:

### ✔ Current Status (v1.0.7)

* **66+ tests**
* **150+ assertions**
* **≈98% coverage**
* **Locking tests now fully deterministic** (Phase 15)
* Queue-mode lock behavior simulated with TTL expiration
* Hybrid lock fallback paths tested against both Redis and file lock modes

---

# 🧾 Release Notes

Updated:

### **v1.0.7 — Phase 15: Redis Lock Testing Stability Update**

* Added `FakeRedisConnection` with TTL simulation.
* Updated `FakeHealthyAdapter` to expose Redis-like commands.
* HybridLockManager queue-mode tests now accurately simulate TTL expiration.
* RedisLockManager updated for method-based detection (not class-type).
* Queue-mode hanging & instant-acquire issues fixed permanently.
* All locking tests now stable and deterministic on all systems.

Complete changelog is available in:
👉 [`CHANGELOG.md`](../CHANGELOG.md)
---
**© 2025 Maatify.dev**

Engineered by **Mohamed Abdulalim ([@megyptm](https://github.com/megyptm))** — https://www.maatify.dev
Released under the [MIT license](../LICENSE).

---
