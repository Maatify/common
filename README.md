![**Maatify.dev**](https://www.maatify.dev/assets/img/img/maatify_logo_white.svg)
---
[![Current version](https://img.shields.io/packagist/v/maatify/common)](https://packagist.org/packages/maatify/common)
[![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/maatify/common)](https://packagist.org/packages/maatify/common)
[![Monthly Downloads](https://img.shields.io/packagist/dm/maatify/common)](https://packagist.org/packages/maatify/common/stats)
[![Total Downloads](https://img.shields.io/packagist/dt/maatify/common)](https://packagist.org/packages/maatify/common/stats)
[![License](https://img.shields.io/github/license/maatify/common)](https://github.com/maatify/common/blob/main/LICENSE)


# 📦 maatify/common

Common Data Transfer Objects (DTOs) and helper utilities shared across all maatify libraries.

---

## ⚙️ Installation

```bash
composer require maatify/common
````

---

## 🧩 Overview

This library provides common DTOs and helper classes that are reused across all maatify packages,
such as `maatify/mongo-activity`, `maatify/psr-logger`, and future maatify projects.

**Current modules:**

* `PaginationHelper` – simple array pagination logic.
* `PaginationDTO` – unified structure for pagination metadata.

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

## 🔐 Lock System (New)

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

## 🧱 Directory Structure

```
src/
├── Pagination/
│   ├── DTO/
│   │   └── PaginationDTO.php
│   ├── Helpers/
│   │   ├── PaginationHelper.php
│   │   └── PaginationResultDTO.php
└── Lock/
    ├── LockInterface.php
    ├── LockModeEnum.php
    ├── FileLockManager.php
    ├── RedisLockManager.php
    ├── HybridLockManager.php
    └── LockCleaner.php
```

---

## 🪪 License

**[MIT license](LICENSE)** © [Maatify.dev](https://www.maatify.dev)

You’re free to use, modify, and distribute this library with attribution.

---

## 👤 Author

**Maatify.dev**
[https://www.Maatify.dev](https://www.Maatify.dev)

