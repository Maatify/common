<?php
/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-11-09
 * Time: 14:26
 * Project: maatify:common
 * IDE: PhpStorm
 * https://www.Maatify.dev
 */

declare(strict_types=1);

namespace Maatify\Common\Traits;

/**
 * 🧠 Trait: SingletonTrait
 *
 * 🧩 Purpose:
 * Implements the Singleton design pattern in a simple and reusable way.
 * Any class that uses this trait gains controlled instance management,
 * ensuring that only one instance exists throughout the application lifecycle.
 *
 * ✅ Features:
 * - Prevents direct instantiation, cloning, or unserialization.
 * - Provides static `obj()` method to retrieve or initialize the instance.
 * - Includes `reset()` for testing or reinitialization scenarios.
 *
 * ⚙️ Example Usage:
 * ```php
 * final class ConfigManager
 * {
 *     use SingletonTrait;
 * }
 *
 * $config = ConfigManager::obj(); // Returns the same instance on every call
 * ConfigManager::reset();         // Creates a new instance
 * ```
 *
 * 🧱 Best Practice:
 * - Use SingletonTrait only for global managers or immutable service classes.
 * - Avoid using it for stateful or request-scoped classes.
 *
 * @package Maatify\Common\Traits
 */
trait SingletonTrait
{
    /**
     * 🧩 Holds the single instance of the class using this trait.
     *
     * @var self|null
     */
    private static ?self $instance = null;

    /**
     * 🚫 Prevent direct instantiation to enforce Singleton behavior.
     */
    private function __construct() {}

    /**
     * 🚫 Prevent object cloning to maintain Singleton integrity.
     */
    private function __clone() {}

    /**
     * 🚫 Prevent unserialization of Singleton instance.
     *
     * @throws \RuntimeException Always thrown to prevent unserialization.
     */
    final public function __wakeup(): void
    {
        throw new \RuntimeException('Cannot unserialize singleton');
    }

    /**
     * 🔁 Retrieve the singleton instance.
     *
     * Automatically initializes the instance if it does not exist.
     *
     * @return self Singleton instance of the calling class.
     *
     * ✅ Example:
     * ```php
     * $logger = Logger::obj();
     * ```
     */
    final public static function obj(): self
    {
        return self::$instance ??= new self();
    }

    /**
     * ♻️ Reset the singleton instance.
     *
     * Useful for testing, reconfiguration, or refreshing the instance.
     *
     * @return void
     *
     * ✅ Example:
     * ```php
     * ConfigManager::reset(); // Recreates instance
     * ```
     */
    final public static function reset(): void
    {
        self::$instance = new self();
    }
}
