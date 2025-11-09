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
 * 🧩 **Class FakeFailingAdapter**
 *
 * 🎯 **Purpose:**
 * Provides a stub (mock) implementation of {@see AdapterInterface} that always fails.
 * It simulates a disconnected or unhealthy Redis adapter for testing fallback mechanisms
 * in components such as {@see \Maatify\Common\Lock\HybridLockManager}.
 *
 * 🧠 **Usage Context:**
 * Used exclusively in test environments to verify:
 * - Fallback behavior when Redis connectivity fails.
 * - Handling of invalid or null adapter connections.
 *
 * ✅ **Expected Behavior:**
 * - `isConnected()` → returns `false`
 * - `getConnection()` → returns `null`
 * - `healthCheck()` → returns `false`
 */
final class FakeFailingAdapter implements AdapterInterface
{
    /**
     * 🚫 Simulate a no-op connection attempt (always fails silently).
     *
     * @return void
     */
    public function connect(): void
    {
        // No actual connection; intentionally does nothing
    }

    /**
     * ❌ Always reports disconnected state.
     *
     * @return bool False indicating no connection.
     */
    public function isConnected(): bool
    {
        return false;
    }

    /**
     * 🚫 Returns null to indicate no underlying connection.
     *
     * @return object|null Always null.
     */
    public function getConnection(): ?object
    {
        return null;
    }

    /**
     * ❌ Simulates a failed health check.
     *
     * @return bool Always false to represent an unhealthy adapter.
     */
    public function healthCheck(): bool
    {
        return false;
    }

    /**
     * 🧹 Simulate disconnection cleanup (no-op).
     *
     * @return void
     */
    public function disconnect(): void
    {
        // No operation needed for the fake adapter
    }
}
