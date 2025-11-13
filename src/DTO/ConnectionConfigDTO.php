<?php
/**
 * @copyright   ©2025 Maatify.dev
 * @Library     maatify/common
 * @Project     maatify:common
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-13 16:17
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/common  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Common\DTO;

/**
 * 🧩 **Class ConnectionConfigDTO**
 *
 * 🎯 **Purpose:**
 * Represents a standardized, immutable configuration Data Transfer Object (DTO)
 * for defining connection settings across supported adapters (MySQL, MongoDB, Redis, etc.).
 *
 * 🧠 **Key Features:**
 * - Provides a unified structure for DSN-based connection setup.
 * - Used for dependency injection and adapter factory initialization.
 * - Immutable (`readonly`) to ensure safe configuration integrity at runtime.
 *
 * ✅ **Example Usage:**
 * ```php
 * use Maatify\Common\DTO\ConnectionConfigDTO;
 *
 * $config = new ConnectionConfigDTO(
 *     dsn: 'mysql:host=127.0.0.1;dbname=maatify',
 *     user: 'root',
 *     pass: 'secret',
 *     options: [
 *         PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
 *     ],
 *     driver: 'pdo',
 *     profile: 'production'
 * );
 * ```
 */
final readonly class ConnectionConfigDTO
{
    /**
     * 🧠 **Constructor**
     *
     * Defines the full connection configuration parameters.
     *
     * @param string      $dsn      Data Source Name string (e.g., `"mysql:host=127.0.0.1;dbname=test"`).
     * @param string|null $user     Optional database username (if applicable).
     * @param string|null $pass     Optional database password (if applicable).
     * @param array       $options  Optional adapter or driver-specific options.
     * @param string|null $driver   Optional driver identifier (e.g., `"pdo"`, `"dbal"`, `"mysqli"`).
     * @param string|null $profile  Optional connection profile name (useful for multi-env setups).
     */
    public function __construct(
        public string $dsn,
        public ?string $user = null,
        public ?string $pass = null,
        public array $options = [],
        public ?string $driver = null,
        public ?string $profile = null,
    ) {}
}
