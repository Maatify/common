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
 * 🎯 **Purpose:**
 * Provides a mock {@see AdapterInterface} implementation that simulates
 * a healthy and responsive Redis-like adapter.
 * Used for testing lock managers and other adapter-based components
 * without connecting to a real Redis server.
 *
 * 🧠 **Behavior Simulation:**
 * - Mimics `connect()`, `set()`, `exists()`, and `del()` operations in memory.
 * - Always reports as connected and healthy.
 * - Supports `NX` flag logic for atomic key acquisition.
 *
 * ✅ **Use Case:**
 * Commonly used in unit tests like {@see \Maatify\Common\Tests\Lock\HybridLockManagerTest}
 * to validate locking logic without requiring external dependencies.
 *
 * ⚙️ **Example Usage:**
 * ```php
 * $adapter = new FakeHealthyAdapter();
 * $adapter->connect();
 * $adapter->set('lock:demo', '1', ['nx' => true]); // simulate Redis SET NX
 * ```
 */
final class FakeHealthyAdapter implements AdapterInterface
{
    /** @var bool Simulated connection state. */
    private bool $connected = false;

    /** @var array<string, string> Internal in-memory key-value store. */
    private array $store = [];

    /**
     * 🔌 Simulate establishing a connection.
     *
     * @return void
     */
    public function connect(): void
    {
        $this->connected = true;
    }

    /**
     * 🔍 Check if the adapter is currently connected.
     *
     * @return bool True if connected, false otherwise.
     */
    public function isConnected(): bool
    {
        return $this->connected;
    }

    /**
     * ⚙️ Retrieve a pseudo-connection handle (returns self).
     *
     * @return object|null Returns `$this` as the connection object.
     */
    public function getConnection(): ?object
    {
        return $this;
    }

    /**
     * 💚 Always report adapter health as OK.
     *
     * @return bool True indicating a healthy adapter.
     */
    public function healthCheck(): bool
    {
        return true;
    }

    /**
     * 🔌 Simulate disconnection from the adapter.
     *
     * @return void
     */
    public function disconnect(): void
    {
        $this->connected = false;
    }

    /**
     * 💾 Simulate Redis `SET` operation with optional NX logic.
     *
     * If `'nx'` is set in options and key already exists, returns false.
     * Otherwise, stores the key-value pair in memory.
     *
     * @param string $key      Lock key name.
     * @param string $value    Stored value.
     * @param array  $options  Optional Redis-style parameters (`nx`, `ex`, etc.).
     *
     * @return bool True if successfully set; false if NX condition fails.
     */
    public function set(string $key, string $value, array $options = []): bool
    {
        if (isset($options['nx']) && isset($this->store[$key])) {
            return false;
        }

        $this->store[$key] = $value;
        return true;
    }

    /**
     * 🔍 Simulate Redis `EXISTS` operation.
     *
     * @param string $key The key to check.
     *
     * @return int Returns 1 if the key exists; otherwise 0.
     */
    public function exists(string $key): int
    {
        return isset($this->store[$key]) ? 1 : 0;
    }

    /**
     * ❌ Simulate Redis `DEL` operation.
     *
     * Removes a key from the in-memory store.
     *
     * @param string $key The key to delete.
     *
     * @return void
     */
    public function del(string $key): void
    {
        unset($this->store[$key]);
    }
}
