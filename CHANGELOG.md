# 🧾 CHANGELOG

All notable changes to **maatify/common** will be documented in this file.
This project follows [Semantic Versioning](https://semver.org/).

---

## [1.0.0] – 2025-11-10

### 🎉 First Stable Release

This marks the first official stable release of the **Maatify Common Library**, serving as the foundation for all other Maatify components.

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
Distributed under the [MIT License](LICENSE).
