<?php
/**
 * @copyright   ©2025 Maatify.dev
 * @Library     maatify/common
 * @Project     maatify:common
 * @author      Mohamed Abdulalim
 * @since       2025-11-09
 * @link        https://github.com/Maatify/common
 */

declare(strict_types=1);

namespace Maatify\Common\Lock;

use Maatify\Common\Contracts\Adapter\AdapterInterface;
use Psr\Log\LoggerInterface;
use Maatify\PsrLogger\LoggerFactory;
use Throwable;

/**
 * ⚙️ **Class HybridLockManager**
 *
 * 🎯 **Purpose:**
 * A smart unified lock manager that automatically chooses between:
 * - A distributed Redis-based lock (via {@see RedisLockManager}) if available and healthy.
 * - A file-based local lock (via {@see FileLockManager}) as a fallback.
 *
 * 🧠 **Key Features:**
 * - Automatically detects and uses a Redis adapter if available.
 * - Falls back seamlessly to file locks when Redis is unreachable.
 * - Supports both **EXECUTION** (non-blocking) and **QUEUE** (blocking) operation modes.
 * - Fully PSR-3 compliant for transparent observability.
 *
 * 🧩 **Typical Use Case:**
 * Used in systems that run on both single-server and distributed setups, providing
 * unified locking logic without requiring configuration changes.
 *
 * ✅ **Example:**
 * ```php
 * use Maatify\Common\Lock\HybridLockManager;
 * use Maatify\Common\Lock\LockModeEnum;
 * use Maatify\Common\Adapters\RedisAdapter;
 *
 * $adapter = new RedisAdapter($config);
 * $adapter->connect();
 *
 * $lock = new HybridLockManager('generate_reports', LockModeEnum::QUEUE, adapter: $adapter);
 * $lock->run(function () {
 *     echo "Executing safely under hybrid lock.";
 * });
 * ```
 */
final class HybridLockManager implements LockInterface
{
    /** @var LockInterface Active lock driver (Redis or File). */
    private LockInterface $driver;

    /** @var LoggerInterface PSR-3 logger instance. */
    private LoggerInterface $logger;

    /** @var LockModeEnum Execution mode (QUEUE or EXECUTION). */
    private LockModeEnum $mode;

    /**
     * 🧩 **Constructor**
     *
     * Initializes the hybrid lock manager and automatically selects
     * the best available lock mechanism based on adapter health.
     *
     * @param string                $key      Lock identifier.
     * @param LockModeEnum          $mode     Determines blocking/non-blocking mode.
     * @param int                   $ttl      Time-to-live in seconds.
     * @param AdapterInterface|null $adapter  Optional Redis adapter (dependency-injected).
     * @param LoggerInterface|null  $logger   Optional custom logger.
     */
    public function __construct(
        string $key,
        LockModeEnum $mode = LockModeEnum::EXECUTION,
        int $ttl = 300,
        ?AdapterInterface $adapter = null,
        ?LoggerInterface $logger = null
    ) {
        $this->mode = $mode;
        $this->logger = $logger ?? LoggerFactory::create('lock/hybrid');

        // 🧠 Prefer Redis if adapter is valid and responsive
        if ($adapter !== null && $this->canUseAdapter($adapter)) {
            $this->driver = new RedisLockManager($key, $adapter, $ttl, $this->logger);
            $this->logger->info("HybridLockManager initialized using Redis adapter for '{$key}'");
        } else {
            // 🧱 Fallback: use file-based lock when Redis unavailable
            $lockFile = sys_get_temp_dir() . "/maatify/locks/{$key}.lock";
            $this->driver = new FileLockManager($lockFile, $ttl, $this->logger);
            $this->logger->info("HybridLockManager fallback to FileLockManager for '{$key}'");
        }
    }

    // -----------------------------------------------------------
    // 🔹 Public Lock Interface Implementation
    // -----------------------------------------------------------

    /**
     * 🔏 Acquire the underlying lock (Redis or File).
     *
     * @return bool True if lock acquired successfully, false otherwise.
     */
    public function acquire(): bool
    {
        return $this->driver->acquire();
    }

    /**
     * 🔍 Check if the current lock is active.
     *
     * @return bool True if locked, false otherwise.
     */
    public function isLocked(): bool
    {
        return $this->driver->isLocked();
    }

    /**
     * 🔓 Release the active lock (regardless of driver type).
     *
     * @return void
     */
    public function release(): void
    {
        $this->driver->release();
    }

    // -----------------------------------------------------------
    // 🔹 Queue Mode Helpers
    // -----------------------------------------------------------

    /**
     * 🕓 **Wait until the lock is available, then acquire it.**
     *
     * Used for blocking (QUEUE) mode, continuously retrying until
     * the lock can be obtained.
     *
     * @param int $retryDelay Delay between retries in microseconds (default: 500,000 µs = 0.5s).
     *
     * @return void
     */
    public function waitAndAcquire(int $retryDelay = 500_000): void
    {
        while (! $this->acquire()) {
            usleep($retryDelay);
        }
    }

    /**
     * 🚀 **Execute a callback under lock protection.**
     *
     * Automatically acquires, executes, and releases the lock.
     * Supports both blocking (QUEUE) and non-blocking (EXECUTION) modes.
     *
     * @param callable $callback    Code block to execute while holding the lock.
     * @param int      $retryDelay  Retry delay (microseconds) for QUEUE mode.
     *
     * @return void
     */
    public function run(callable $callback, int $retryDelay = 500_000): void
    {
        if ($this->mode === LockModeEnum::QUEUE) {
            $this->waitAndAcquire($retryDelay);
        } elseif (! $this->acquire()) {
            return; // Skip silently in non-blocking mode
        }

        try {
            $callback();
        } finally {
            $this->release();
        }
    }

    // -----------------------------------------------------------
    // 🔹 Adapter Validation Logic
    // -----------------------------------------------------------

    /**
     * 🧠 **Check if a Redis adapter is healthy and ready to use.**
     *
     * Ensures connection and verifies health status before switching
     * to Redis-based locking.
     *
     * @param AdapterInterface $adapter The Redis-compatible adapter.
     *
     * @return bool True if adapter connection is stable and usable.
     */
    private function canUseAdapter(AdapterInterface $adapter): bool
    {
        try {
            if (! $adapter->isConnected()) {
                $adapter->connect();
            }

            return $adapter->healthCheck();
        } catch (Throwable $e) {
            $this->logger->warning('Adapter health check failed — falling back to FileLockManager.', [
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }
}
