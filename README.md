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
use Maatify\Common\Helpers\PaginationHelper;

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
use Maatify\Common\DTO\PaginationDTO;

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

## 🧱 Directory Structure

```
src/
├── DTO/
│   └── PaginationDTO.php
└── Helpers/
    └── PaginationHelper.php
```

---

## 🪪 License

**[MIT license](LICENSE)** © [Maatify.dev](https://www.maatify.dev)

You’re free to use, modify, and distribute this library with attribution.

---

## 👤 Author

**Maatify.dev**
[https://www.Maatify.dev](https://www.Maatify.dev)

---

