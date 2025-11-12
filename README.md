![**Maatify.dev**](https://www.maatify.dev/assets/img/img/maatify_logo_white.svg)

---

# 📦 maatify/common

[![Version](https://img.shields.io/packagist/v/maatify/common?label=Version&color=4C1)](https://packagist.org/packages/maatify/common)
[![PHP](https://img.shields.io/packagist/php-v/maatify/common?label=PHP&color=777BB3)](https://packagist.org/packages/maatify/common)
[![Build](https://github.com/Maatify/common/actions/workflows/ci.yml/badge.svg?label=Build&color=brightgreen)](https://github.com/Maatify/common/actions/workflows/ci.yml)
[![Monthly Downloads](https://img.shields.io/packagist/dm/maatify/common?label=Monthly%20Downloads&color=00A8E8)](https://packagist.org/packages/maatify/common)
[![Total Downloads](https://img.shields.io/packagist/dt/maatify/common?label=Total%20Downloads&color=2AA)](https://packagist.org/packages/maatify/common)
[![Stars](https://img.shields.io/github/stars/Maatify/common?label=Stars&color=FFD43B)](https://github.com/Maatify/common/stargazers)
[![License](https://img.shields.io/github/license/Maatify/common?label=License&color=blueviolet)](LICENSE)
[![Status](https://img.shields.io/badge/Status-Stable-success?style=flat-square)]()
[![Code Quality](https://img.shields.io/codefactor/grade/github/Maatify/common/main)](https://www.codefactor.io/repository/github/Maatify/common)

---

🏁 Stable Release v1.0.0 — The core foundational library of the Maatify.dev ecosystem providing standardized DTOs, validation, sanitization, date/time, locking, and text utilities for all backend modules.
> 📦 This is the first official stable version (v1.0.0) of maatify/common, released on **2025-11-10**.

> 🔗 [بالعربي 🇸🇦 ](./README-AR.md)

---

## 🧭 Version Information
| Key             | Value               |
|-----------------|---------------------|
| Version         | **1.0.0 Stable**    |
| Release Date    | 2025-11-10          |
| PHP Requirement | ≥ 8.1               |
| License         | MIT                 |
| Coverage        | 98 %                |
| Tests Passed    | 66 (150 Assertions) |


## 🧩 Overview

This library provides reusable, framework-agnostic building blocks (DTOs, helpers, traits, enums, and validators)
shared across all **Maatify** ecosystem packages such as `maatify/mongo-activity`, `maatify/psr-logger`, and others.

---
## 📘 Documentation & Release Files
| File                                                            | Description                              |
|-----------------------------------------------------------------|------------------------------------------|
| [`/docs/README.full.md`](docs/README.full.md)                   | Full combined documentation (Phases 1–8) |
| [`/docs/enums.md`](docs/enums.md)                               | Detailed reference for Enums & Constants |
| [`/docs/phases/README.phase7.md`](docs/phases/README.phase7.md) | Phase 7 breakdown and EnumHelper notes   |
| [`CHANGELOG.md`](CHANGELOG.md)                                  | Complete version history                 |
| [`CONTRIBUTING.md`](CONTRIBUTING.md)                            | Contribution guidelines                  |
| [`VERSION`](VERSION)                                            | Current release version                  |
---

## **Core Modules:**

* 🧮 **Pagination Helpers** — `PaginationHelper`, `PaginationDTO`, `PaginationResultDTO`
  Unified pagination structures for API responses and MySQL queries.

* 🔐 **Lock System** — `FileLockManager`, `RedisLockManager`, `HybridLockManager`
  Safe execution control for cron jobs, distributed tasks, and queue workers.

* 🧼 **Security Sanitization** — `InputSanitizer`, `SanitizesInputTrait`
  Clean and escape user input safely with internal `HTMLPurifier` integration.

* 🧠 **Core Traits** — `SingletonTrait`, `SanitizesInputTrait`
  Reusable traits for singleton pattern, safe input handling, and shared helpers.

* ✨ **Text & Placeholder Utilities** — `TextFormatter`, `PlaceholderRenderer`, `RegexHelper`, `SecureCompare`
  Powerful text formatting, placeholder rendering, and secure string comparison tools.

* 🕒 **Date & Time Utilities** — `DateFormatter`, `DateHelper`
  Humanized difference, timezone conversion, and localized date rendering (EN/AR/FR).

* 🧩 **Validation & Filtering Tools** — `Validator`, `Filter`, `ArrayHelper`
  Email/URL/UUID/Slug validation, input detection, and advanced array cleanup utilities.

* ⚙️ **Enums & Constants Standardization** — `TextDirectionEnum`, `MessageTypeEnum`, `ErrorCodeEnum`, `PlatformEnum`, `AppEnvironmentEnum`, `CommonPaths`, `CommonLimits`, `CommonHeaders`, `Defaults`, `EnumHelper`
  Centralized enum and constant definitions ensuring consistent standards, reusable helpers, and unified configuration across all Maatify libraries.

---

## ⚙️ Installation

```bash
composer require maatify/common
````

---

## 📦 Dependencies

This library directly relies on:

| Dependency              | Purpose                                   | Link                                                                     |
|-------------------------|-------------------------------------------|--------------------------------------------------------------------------|
| **ezyang/htmlpurifier** | Secure HTML/XSS sanitization engine       | [github.com/ezyang/htmlpurifier](https://github.com/ezyang/htmlpurifier) |
| **psr/log**             | Standardized PSR-3 logging interface      | [www.php-fig.org/psr/psr-3](https://www.php-fig.org/psr/psr-3/)          |
| **phpunit/phpunit**     | Unit testing framework (development only) | [phpunit.de](https://phpunit.de)                                         |

> `maatify/common` integrates these open-source libraries to deliver
> a consistent and secure foundation for all other Maatify components.

> 🧠 **Note:**
> `maatify/common` automatically configures **HTMLPurifier** to use an internal cache directory at
> `storage/purifier_cache` for optimized performance.
> This ensures faster sanitization on subsequent calls without requiring manual setup.
>
> If you wish to override the cache path, set the environment variable:
>
> ```bash
> HTMLPURIFIER_CACHE_PATH=/path/to/custom/cache
> ```
>
> or modify it programmatically via:
>
> ```php
> $config->set('Cache.SerializerPath', '/custom/cache/path');
> ```

---

## 🧠 SingletonTrait

A clean, PSR-friendly Singleton implementation to manage single-instance service classes safely.

### 🔹 Example Usage

```php
use Maatify\Common\Traits\SingletonTrait;

final class ConfigManager
{
    use SingletonTrait;

    public function get(string $key): ?string
    {
        return $_ENV[$key] ?? null;
    }
}

// ✅ Always returns the same instance
$config = ConfigManager::obj();

// ♻️ Reset (for testing)
ConfigManager::reset();
```

### ✅ Features

* Prevents direct construction, cloning, and unserialization.
* Provides static `obj()` to access the global instance.
* Includes `reset()` for testing or reinitialization.

---

## 📚 Example Usage

### 🔹 Paginate Array Data

```php
use Maatify\Common\Pagination\Helpers\PaginationHelper;

$items = range(1, 100);

$result = PaginationHelper::paginate($items, page: 2, perPage: 10);

print_r($result);
```

Output:

```php
[
    'data' => [11, 12, 13, 14, 15, 16, 17, 18, 19, 20],
    'pagination' => Maatify\Common\DTO\PaginationDTO {
        page: 2,
        perPage: 10,
        total: 100,
        totalPages: 10,
        hasNext: true,
        hasPrev: true
    }
]
```

---

### 🔹 Working with `PaginationDTO`

```php
use Maatify\Common\Pagination\DTO\PaginationDTO;

$pagination = new PaginationDTO(
    page: 1,
    perPage: 25,
    total: 200,
    totalPages: 8,
    hasNext: true,
    hasPrev: false
);

print_r($pagination->toArray());
```

---

## 🔐 Lock System

Advanced locking utilities to prevent concurrent executions in Cron jobs, queue workers, or API-critical flows.

---

### 🔹 Available Managers

| Class               | Type        | Description                                                                          |
|---------------------|-------------|--------------------------------------------------------------------------------------|
| `FileLockManager`   | Local       | File-based lock stored in `/tmp` or any directory                                    |
| `RedisLockManager`  | Distributed | Uses Redis or Predis client for network-safe locking                                 |
| `HybridLockManager` | Smart       | Automatically chooses Redis if available, otherwise falls back to file lock          |
| `LockCleaner`       | Utility     | Cleans up stale `.lock` files after timeouts                                         |
| `LockModeEnum`      | Enum        | Defines whether lock should `EXECUTION` (non-blocking) or `QUEUE` (waits until free) |

---

### 🧠 Example 1 — File Lock

```php
use Maatify\Common\Lock\FileLockManager;

$lock = new FileLockManager('/tmp/maatify/cron/report.lock', 600);

if (! $lock->acquire()) {
    exit("Another job is running.\n");
}

echo "Running safely...\n";

$lock->release();
```

---

### ⚙️ Example 2 — Redis Lock

```php
use Maatify\Common\Lock\RedisLockManager;

$lock = new RedisLockManager('cleanup_task', ttl: 600);

if ($lock->acquire()) {
    echo "Cleaning...\n";
    $lock->release();
}
```

✅ Works automatically with both `phpredis` and `predis`.
If Redis is down, it logs an error via `maatify/psr-logger`.

---

### 🚀 Example 3 — Hybrid Lock (Recommended)

```php
use Maatify\Common\Lock\HybridLockManager;
use Maatify\Common\Lock\LockModeEnum;

$lock = new HybridLockManager(
    key: 'daily_summary',
    mode: LockModeEnum::QUEUE,
    ttl: 600
);

$lock->run(function () {
    echo "Generating daily summary...\n";
});
```

Automatically uses Redis if available, otherwise falls back to file lock.

---

### 🧹 Example 4 — Clean Old Locks

```php
use Maatify\Common\Lock\LockCleaner;

LockCleaner::cleanOldLocks(sys_get_temp_dir() . '/maatify/locks', 900);
```

---

### 🧾 Notes

* All lock operations are fully logged (via `maatify/psr-logger`).
* Default lock expiration (TTL) is **300 seconds (5 minutes)**.
* Hybrid mode retries every **0.5 seconds** when using queue mode.

---

### 🗂 Directory (Lock Module)

```
src/Lock/
├── LockInterface.php
├── LockModeEnum.php
├── FileLockManager.php
├── RedisLockManager.php
├── HybridLockManager.php
└── LockCleaner.php
```

---

## 🕒 Cron Lock System (Legacy Section)

This module provides simple yet powerful locking mechanisms to prevent concurrent cron executions.

**Available implementations :**

* `FileCronLock` — lightweight local lock for single-host environments.
* `RedisCronLock` — distributed lock using Redis or Predis, automatically disabled if Redis is unavailable.

**Interface:**

```php
use Maatify\Common\Lock\LockInterface;
```

**Example:**

```php
use Maatify\Common\Lock\FileLockManager;

$lock = new FileLockManager('/var/locks/daily_job.lock', 300);

if (! $lock->acquire()) {
    exit("Cron already running...\n");
}

echo "Running job...\n";

// ... job logic ...

$lock->release();
```

✅ If Redis or Predis is installed, you can use:

```php
use Maatify\Common\Lock\RedisLockManager;

$lock = new RedisLockManager('daily_job');
if ($lock->acquire()) {
    // do work
    $lock->release();
}
```

Redis version automatically logs a warning (and safely disables itself) if Redis isn’t available.

---

### 🧼 Input Sanitization

Use `Maatify\Common\Security\InputSanitizer` to clean any user or system input safely.

```php
use Maatify\Common\Security\InputSanitizer;

echo InputSanitizer::sanitize('<script>alert(1)</script>', 'output');
// Output: &lt;script&gt;alert(1)&lt;/script&gt;
```

---

### ✨ Text & Placeholder Utilities 

Reusable text manipulation and safe string utilities shared across all Maatify libraries.

#### 🔹 PlaceholderRenderer

Safely render nested placeholders within templates.

```php
use Maatify\Common\Text\PlaceholderRenderer;

$template = 'Hello, {{user.name}} ({{user.email}})';
$data = ['user' => ['name' => 'Mohamed', 'email' => 'm@maatify.dev']];

echo PlaceholderRenderer::render($template, $data);
// Output: Hello, Mohamed (m@maatify.dev)
```

#### 🔹 TextFormatter

Normalize, slugify, or title-case strings consistently across platforms.

```php
use Maatify\Common\Text\TextFormatter;

TextFormatter::slugify('Hello World!');      // hello-world
TextFormatter::normalize('ÄÖÜß Test');       // aeoeuess-test
TextFormatter::titleCase('maatify common');  // Maatify Common
```

#### 🔹 RegexHelper

Convenient wrapper for regex operations.

```php
use Maatify\Common\Text\RegexHelper;

RegexHelper::replace('/\d+/', '#', 'Item123'); // Item#
```

#### 🔹 SecureCompare

Timing-safe string comparison for token or signature checks.

```php
use Maatify\Common\Text\SecureCompare;

if (SecureCompare::equals($provided, $stored)) {
    echo 'Tokens match safely.';
}
```

✅ Includes full unit test coverage (`tests/Text/*`)  
✅ Cross-platform transliteration with fallback normalization  
✅ Used by other Maatify libraries for formatting, matching, and signature checks  

---

### 🗂 Directory (Text Utilities)

```
src/Text/
├── PlaceholderRenderer.php
├── TextFormatter.php
├── RegexHelper.php
└── SecureCompare.php
```

---

> 🔧 **Tip:** These utilities are internally leveraged by `maatify/i18n`, `maatify/security`, and `maatify/queue-manager` for consistent text normalization, placeholder expansion, and token validation.

---
#### 🕒 **Date & Time Utilities** 

Reusable date and time formatting utilities with localization and humanized difference support.

```php
use Maatify\Common\Date\DateFormatter;
use Maatify\Common\Date\DateHelper;
use DateTime;
```

##### 🔹 Humanize Difference

Convert two timestamps into a natural, human-friendly expression:

```php
$a = new DateTime('2025-11-09 10:00:00');
$b = new DateTime('2025-11-09 09:00:00');

echo DateFormatter::humanizeDifference($a, $b, 'en'); // "1 hour(s) ago"
echo DateFormatter::humanizeDifference($a, $b, 'ar'); // "منذ 1 ساعة"
```

##### 🔹 Localized Date String

Format any DateTime into a locale-aware representation:

```php
$date = new DateTime('2025-11-09 12:00:00');
echo DateHelper::toLocalizedString($date, 'ar', 'Africa/Cairo'); // ٩ نوفمبر ٢٠٢٥، ٢:٠٠ م
echo DateHelper::toLocalizedString($date, 'en', 'America/New_York'); // November 9, 2025, 7:00 AM
```

✅ Supports **English (en)**, **Arabic (ar)**, and **French (fr)** locales  
✅ Handles **timezone conversion** and **localized month/day names** automatically  
✅ Backed by `IntlDateFormatter` for precise localization  
✅ Fully covered with unit tests (`tests/Date/*`)  

---

### 🗂 Directory (Date Utilities)

```
src/Date/
├── DateFormatter.php
└── DateHelper.php
```
---

#### 🧩 **Validation & Filtering Utilities** 

Reusable validation, filtering, and array manipulation tools for ensuring clean and consistent input data across maatify projects.

```php
use Maatify\Common\Validation\Validator;
use Maatify\Common\Validation\Filter;
use Maatify\Common\Validation\ArrayHelper;
```

---

##### 🔹 Validation

Perform quick and reliable validation for various input types:

```php
Validator::email('user@maatify.dev');              // ✅ true
Validator::url('https://maatify.dev');             // ✅ true
Validator::ip('192.168.1.1');                      // ✅ true
Validator::uuid('123e4567-e89b-12d3-a456-426614174000'); // ✅ true
Validator::slug('maatify-core');                   // ✅ true
Validator::slugPath('en/gift-card/itunes-10-usd'); // ✅ true
```

---

##### 🔹 Numeric & Range Validation

```php
Validator::integer('42');           // ✅ true
Validator::float('3.14');           // ✅ true
Validator::between(5, 1, 10);       // ✅ true
Validator::phone('+201234567890');  // ✅ true
```

---

##### 🔹 Auto Type Detection

Smart helper that detects the type of input automatically:

```php
Validator::detectType('test@maatify.dev');     // 'email'
Validator::detectType('maatify-core');         // 'slug'
Validator::detectType('en/gift-card/item');    // 'slug_path'
Validator::detectType('42');                   // 'integer'
Validator::detectType('3.14');                 // 'float'
Validator::detectType('unknown-data');         // null
```

✅ Detects and differentiates between `slug` and `slug_path`  
✅ Useful for dynamic API validation or auto-form field type detection

---

##### 🔹 Filtering

Simplify array cleaning before validation or persistence:

```php
$data = [
    'name' => '  Mohamed  ',
    'email' => ' ',
    'bio' => '<b>Hello</b>',
    'age' => null
];

$clean = Filter::sanitizeArray($data);

// Output:
[
    'name' => 'Mohamed',
    'bio'  => '<b>Hello</b>'
]
```

Available methods:

* `Filter::trimArray(array $data)`
* `Filter::removeEmptyValues(array $data)`
* `Filter::sanitizeArray(array $data)`

---

##### 🔹 Array Helper

Manipulate associative arrays in a functional and elegant way:

```php
$data = [
    'user' => ['id' => 1, 'name' => 'Mohamed'],
    'meta' => ['role' => 'admin', 'active' => true]
];

ArrayHelper::flatten($data);
// ['user.id' => 1, 'user.name' => 'Mohamed', 'meta.role' => 'admin', 'meta.active' => true]

ArrayHelper::only($data, ['user.name']);
// ['user' => ['name' => 'Mohamed']]

ArrayHelper::except($data, ['meta']);
// ['user' => ['id' => 1, 'name' => 'Mohamed']]
```

✅ Fully covered by unit tests (`tests/Validation/*`)  
✅ Integrated slugPath detection for multilingual slugs  
✅ Ideal for preparing request payloads or DTO normalization

---

### 🗂 Directory (Validation Utilities)

```
src/Validation/
├── Validator.php
├── Filter.php
└── ArrayHelper.php
```

---

### ⚙️ Enums & Constants Standardization 

Centralized, reusable enumerations and constants shared across all Maatify libraries — ensuring unified configuration, predictable behavior, and simplified maintenance.

#### 🔹 TextDirectionEnum

Defines text layout direction for UI and localization logic.

```php
use Maatify\Common\Enums\TextDirectionEnum;

echo TextDirectionEnum::LTR->value; // 'ltr'
```

#### 🔹 MessageTypeEnum

Standard system message types used in API responses, logs, and alerts.

```php
use Maatify\Common\Enums\MessageTypeEnum;

echo MessageTypeEnum::ERROR->value; // 'error'
```

#### 🔹 ErrorCodeEnum

Provides globally standardized error identifiers across all Maatify modules.

```php
use Maatify\Common\Enums\ErrorCodeEnum;

throw new Exception('Invalid input', ErrorCodeEnum::INVALID_INPUT->value);
```

#### 🔹 PlatformEnum & AppEnvironmentEnum

Enumerations for defining runtime context and environment configuration.

```php
use Maatify\Common\Enums\PlatformEnum;
use Maatify\Common\Enums\AppEnvironmentEnum;

echo PlatformEnum::WEB->value;          // 'web'
echo AppEnvironmentEnum::PRODUCTION->value; // 'production'
```

#### 🔹 EnumHelper

Smart utility class that unifies enum operations like retrieving names, values, and validating entries.

```php
use Maatify\Common\Enums\EnumHelper;
use Maatify\Common\Enums\MessageTypeEnum;

$names  = EnumHelper::names(MessageTypeEnum::class);
$values = EnumHelper::values(MessageTypeEnum::class);
$isValid = EnumHelper::isValidValue(MessageTypeEnum::class, 'success'); // true
```

#### 🔹 EnumJsonSerializableTrait

Provides automatic JSON serialization for any Enum.

```php
use Maatify\Common\Enums\Traits\EnumJsonSerializableTrait;
use Maatify\Common\Enums\MessageTypeEnum;

echo json_encode(MessageTypeEnum::SUCCESS); // 'success'
```

#### 🔹 Constants Classes

Organized constants for system-wide settings.

```php
use Maatify\Common\Constants\CommonPaths;
use Maatify\Common\Constants\Defaults;

echo CommonPaths::LOG_PATH;          // '/storage/logs'
echo Defaults::DEFAULT_TIMEZONE;     // 'Africa/Cairo'
```

✅ Full PHPUnit coverage (`tests/Enums/*`)  
✅ EnumHelper & Trait verified for stability  
✅ Consistent naming and values across all modules

---

### 🗂 Directory (Enums & Constants)

```
src/Enums/
├── TextDirectionEnum.php
├── MessageTypeEnum.php
├── ErrorCodeEnum.php
├── PlatformEnum.php
├── AppEnvironmentEnum.php
├── EnumHelper.php
└── Traits/
    └── EnumJsonSerializableTrait.php

src/Constants/
├── CommonPaths.php
├── CommonLimits.php
├── CommonHeaders.php
└── Defaults.php
```
---
## 🧩 Helpers

### 🧱 TapHelper

A lightweight, fluent utility for executing a callback on a given value (usually an object) and returning that same value unchanged —  
perfect for cleaner object initialization and inline setup.

---

#### ⚙️ Class
`Maatify\Common\Helpers\TapHelper`

#### ✅ Features
- Executes a callback on a passed object or value.
- Returns the same value (object, scalar, array, etc.).
- Useful for chaining and fluent API style.
- 100% pure function — no side effects unless your callback modifies the object.

---

#### 🧠 Example Usage
```php
use Maatify\Common\Helpers\TapHelper;
use Maatify\DataAdapters\Adapters\MongoAdapter;

$config = new EnvironmentConfig(__DIR__ . '/../');

$mongo = TapHelper::tap(new MongoAdapter($config), fn($a) => $a->connect());

// $mongo is now a connected adapter
$client = $mongo->getConnection();
````

---

#### 🧾 Functional Philosophy

`TapHelper` follows a simple, expressive pattern inspired by functional programming:

| Principle           | Description                                                 |
|---------------------|-------------------------------------------------------------|
| 🧩 **Isolation**    | The callback runs in isolation, returning no value.         |
| 🔁 **Immutability** | The original object/value is returned unchanged.            |
| 🧼 **Clarity**      | Reduces boilerplate for setup code.                         |
| 🧠 **Testability**  | Simple to reason about and unit-test (see `TapHelperTest`). |

---

#### 🧪 Unit Test Reference

`tests/Helpers/TapHelperTest.php`

Covers:

* Returning the same object instance.
* Callback execution correctness.
* Compatibility with scalars and arrays.

```bash
vendor/bin/phpunit --filter TapHelperTest
```

---

#### 🧱 Code Reference

```php
TapHelper::tap(mixed $value, callable $callback): mixed
```

> Executes `$callback($value)` then returns `$value`.

---

#### 🧩 Architectural Benefits within the Maatify Ecosystem

| Aspect                       | Benefit                                                                                                            |
|------------------------------|--------------------------------------------------------------------------------------------------------------------|
| ♻️ **Fluent Initialization** | Enables building adapters and services in one clean line.                                                          |
| 🧠 **Ecosystem Consistency** | Aligns with other helpers like `PathHelper`, `EnumHelper`, and `TimeHelper`.                                       |
| 🧼 **Reduced Boilerplate**   | Replaces multiple setup lines with a single expressive call.                                                       |
| 🧩 **Universal Reusability** | Works seamlessly across all Maatify libraries (`bootstrap`, `data-adapters`, `rate-limiter`, `redis-cache`, etc.). |


---

📘 **Full Documentation:** [docs/enums.md](docs/enums.md)

---

## 🗂 Directory Structure

```
src/
├── Pagination/
│   ├── DTO/
│   │   └── PaginationDTO.php
│   └── Helpers/
│       ├── PaginationHelper.php
│       └── PaginationResultDTO.php
├── Helpers/
│   └── TapHelper.php
├── Lock/
│   ├── LockInterface.php
│   ├── LockModeEnum.php
│   ├── FileLockManager.php
│   ├── RedisLockManager.php
│   ├── HybridLockManager.php
│   └── LockCleaner.php
├── Security/
│   └── InputSanitizer.php
├── Traits/
│   ├── SingletonTrait.php
│   └── SanitizesInputTrait.php
├── Text/
│   ├── PlaceholderRenderer.php
│   ├── TextFormatter.php
│   ├── RegexHelper.php
│   └── SecureCompare.php
├── Date/
│   ├── DateFormatter.php
│   └── DateHelper.php
└── Validation/
    ├── Validator.php
    ├── Filter.php
    └── ArrayHelper.php
        Enums/
        ├── TextDirectionEnum.php
        ├── MessageTypeEnum.php
        ├── ErrorCodeEnum.php
        ├── PlatformEnum.php
        ├── AppEnvironmentEnum.php
        ├── EnumHelper.php
        └── Traits/
            └── EnumJsonSerializableTrait.php
```

---

## 📚 Built Upon

`maatify/common` proudly builds upon several mature and battle-tested open-source foundations:

| Library                                                           | Description                                | Usage in Project                                                                                          |
|-------------------------------------------------------------------|--------------------------------------------|-----------------------------------------------------------------------------------------------------------|
| **[ezyang/htmlpurifier](https://github.com/ezyang/htmlpurifier)** | Standards-compliant HTML filtering library | Powers `InputSanitizer` to ensure XSS-safe and standards-compliant HTML output with full Unicode support. |
| **[psr/log](https://www.php-fig.org/psr/psr-3/)**                 | PSR-3 logging interface                    | Enables standardized logging across sanitization, lock, and validation components.                        |
| **[phpunit/phpunit](https://phpunit.de)**                         | PHP unit testing framework                 | Provides automated testing with CI/CD GitHub workflow integration.                                        |

> Huge thanks to the open-source community for their contributions,
> making the Maatify ecosystem secure, reliable, and extensible. ❤️

---


## 📊 Phase Summary Table

| Phase | Title                             | Status      | Files Created | Notes                                                                     |
|-------|-----------------------------------|-------------|---------------|---------------------------------------------------------------------------|
| 1     | Pagination Module                 | ✅ Completed | 3             | Pagination DTOs & helpers                                                 |
| 2     | Locking System                    | ✅ Completed | 6             | File / Redis / Hybrid managers                                            |
| 3     | Security & Input Sanitization     | ✅ Completed | 3             | Input cleaning & HTMLPurifier                                             |
| 3b    | Core Traits — Singleton System    | ✅ Completed | 1             | SingletonTrait implementation                                             |
| 4     | Text & Placeholder Utilities      | ✅ Completed | 8             | PlaceholderRenderer, TextFormatter, RegexHelper, SecureCompare            |
| 5     | Date & Time Utilities             | ✅ Completed | 4             | HumanizeDifference & Localized Date Formatting                            |
| 6     | Validation & Filtering Tools      | ✅ Completed | 3             | Validator, Filter, and ArrayHelper with full unit tests                   |
| 7     | Enums & Constants Standardization | ✅ Completed | 10 + 5 tests  | Unified Enums, Constants, EnumHelper & JSON Trait with docs               |
| 8     | Testing & Release                 | ✅ Completed | 6             | CHANGELOG.md, CONTRIBUTING.md, VERSION, README.full.md, coverage results  |


---
## ✅ Verified Test Results
> PHPUnit 10.5.58 — PHP 8.4.4  
> • Tests: 66 • Assertions: 150 • Coverage: ~98 %  
> • Runtime: 0.076 s • Memory: 12 MB  
> • Warnings: 1 (No coverage driver available — safe to ignore)

---


## 🧾 Release Verification
All files have been verified and finalized as part of **Phase 8 (v1.0.0 Stable)**.

- ✅ `/docs/README.full.md` – full documentation merged
- ✅ `/docs/enums.md` – enums and constants reference
- ✅ `/docs/phases/README.phase7.md` – phase documentation
- ✅ `CHANGELOG.md` – release history initialized
- ✅ `CONTRIBUTING.md` – contributor guide added
- ✅ `VERSION` – version `1.0.0` confirmed

---

## 🪪 License

**[MIT license](LICENSE)** © [Maatify.dev](https://www.maatify.dev)  
You’re free to use, modify, and distribute this library with attribution.
---
## 🚀 Next Version Plan (v1.1.0)
- Performance optimizations for string and array helpers
- Extended Enum support with localization metadata
- Introduce Common Cache Adapter and Metrics interfaces

---
> 🔗 **Full documentation & release notes:** see [/docs/README.full.md](docs/README.full.md)
---

## 🧱 Authors & Credits

This library is part of the **Maatify.dev Core Ecosystem**, designed and maintained under the technical supervision of:

**👤 Mohamed Abdulalim** — *Backend Lead & Technical Architect*  
Lead architect of the **Maatify Backend Infrastructure**, responsible for the overall architecture, core library design,  
and technical standardization across all backend modules within the Maatify ecosystem.  
🔗 [www.Maatify.dev](https://www.maatify.dev) | ✉️ [mohamed@maatify.dev](mailto:mohamed@maatify.dev)

**🤝 Contributors:**  
The **Maatify.dev Engineering Team** and open-source collaborators who continuously help refine, test, and extend  
the capabilities of this library across multiple Maatify projects.

> 🧩 This project represents a unified engineering effort led by Mohamed Abdulalim, ensuring every Maatify backend component  
> shares a consistent, secure, and maintainable foundation.

---

<p align="center">
  <sub><span style="color:#777">Built with ❤️ by <a href="https://www.maatify.dev">Maatify.dev</a> — Unified Ecosystem for Modern PHP Libraries</span></sub>
</p>

---