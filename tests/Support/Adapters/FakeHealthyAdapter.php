<?php
/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/common
 * @Project     maatify:common
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-09 22:13
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/common  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Common\Tests\Support\Adapters;

use Maatify\Common\Contracts\Adapter\AdapterInterface;

/**
 * 🧩 **Class FakeHealthyAdapter**
 *
 * 🎯 **Purpose**
 * A mock implementation of {@see AdapterInterface} that simulates a fully healthy,
 * functioning Redis-like adapter entirely in memory.
 * It is designed to support test suites that require predictable key-value operations
 * without connecting to an external Redis instance.
 *
 * 🧠 **Behavior Overview**
 * - Maintains an internal in-memory store.
 * - Simulates `SET`, `EXISTS`, and `DEL` operations.
 * - Implements NX locking logic (`SET key value NX`).
 * - Always reports healthy (`healthCheck()` → `true`).
 * - Returns itself as connection handle (`getConnection()`).
 *
 * 🔧 **Typical Use Cases**
 * - Unit tests for locking systems (e.g., HybridLockManager).
 * - Testing atomic key acquisition logic.
 * - Components that need a lightweight simulated adapter.
 *
 * ⚙️ **Example**
 * ```php
 * $adapter = new FakeHealthyAdapter();
 * $adapter->connect();
 *
 * $adapter->set('lock:example', '1', ['nx' => true]); // Acquire lock
 * $exists = $adapter->exists('lock:example');          // Returns 1
 *
 * $adapter->del('lock:example');                       // Remove lock
 * ```
 */
final class FakeHealthyAdapter implements AdapterInterface
{
    /**
     * 🔌 Simulated connection state.
     *
     * @var bool
     */
    private bool $connected = false;

    /**
     * 🗄️ Internal in-memory store used to mimic Redis key-value behavior.
     *
     * @var array<string, string>
     */
    private array $store = [];

    /**
     * 🔌 Establish a simulated connection.
     *
     * @return void
     */
    public function connect(): void
    {
        $this->connected = true;
    }

    /**
     * 🔍 Check if the adapter is "connected".
     *
     * @return bool True when connected, false otherwise.
     */
    public function isConnected(): bool
    {
        return $this->connected;
    }

    /**
     * ⚙️ Returns a pseudo-connection object.
     *
     * @return object|null `$this` acting as the simulated connection handler.
     */
    public function getConnection(): ?object
    {
        return $this;
    }

    /**
     * 💚 Always reports healthy status.
     *
     * @return bool True indicating adapter health is OK.
     */
    public function healthCheck(): bool
    {
        return true;
    }

    /**
     * 🔌 Close simulated connection.
     *
     * @return void
     */
    public function disconnect(): void
    {
        $this->connected = false;
    }

    /**
     * 💾 Simulates Redis `SET` with optional NX locking behavior.
     *
     * If `'nx' => true` is provided and the key already exists,
     * the method returns `false` without modifying the store.
     *
     * @param string $key     The key to assign.
     * @param string $value   The associated value.
     * @param array  $options Optional Redis-style flags (`nx`, `ex`, etc.).
     *
     * @return bool True on success, false if NX rule blocks operation.
     */
    public function set(string $key, string $value, array $options = []): bool
    {
        // Enforce NX rule: fail if key exists
        if (isset($options['nx']) && isset($this->store[$key])) {
            return false;
        }

        $this->store[$key] = $value;
        return true;
    }

    /**
     * 🔍 Simulates Redis `EXISTS` command.
     *
     * @param string $key The key to check.
     *
     * @return int 1 if key exists, otherwise 0.
     */
    public function exists(string $key): int
    {
        return isset($this->store[$key]) ? 1 : 0;
    }

    /**
     * ❌ Simulates Redis `DEL` command.
     *
     * @param string $key The key to delete.
     *
     * @return void
     */
    public function del(string $key): void
    {
        unset($this->store[$key]);
    }

    /**
     * 🏷️ Fake driver identifier.
     *
     * @return string Always returns "fake".
     */
    public function getDriver(): string
    {
        return 'fake';
    }
}
