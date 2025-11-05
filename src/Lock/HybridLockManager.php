<?php
/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-11-06
 * Time: 00:32
 * Project: maatify:common
 * IDE: PhpStorm
 * https://www.Maatify.dev
 */

declare(strict_types=1);

namespace Maatify\Common\Lock;

use Psr\Log\LoggerInterface;
use Maatify\PsrLogger\LoggerFactory;
use Throwable;

/**
 * Class HybridLockManager
 *
 * 🧩 **Smart Unified Lock System**
 * A hybrid lock that automatically selects between Redis-based and File-based
 * locking mechanisms — ensuring your critical jobs never run in parallel.
 *
 * 🔹 Features:
 * - Auto-detects Redis availability and falls back to file locking.
 * - Supports both {@see LockModeEnum::EXECUTION} and {@see LockModeEnum::QUEUE}.
 * - PSR-3 logging for full visibility of lock source and failures.
 * - Safe for use across multiple processes or distributed environments.
 *
 * Example:
 * ```php
 * $lock = new HybridLockManager('reports_generate', LockModeEnum::QUEUE);
 * $lock->run(function () {
 *     // critical job logic
 * });
 * ```
 */
final class HybridLockManager implements LockInterface
{
    private LockInterface $driver;
    private LoggerInterface $logger;
    private LockModeEnum $mode;

    /**
     * HybridLockManager constructor.
     *
     * Automatically selects between Redis and File-based locking.
     *
     * @param string                $key            Unique lock identifier (shared across servers).
     * @param LockModeEnum          $mode           Lock mode: EXECUTION (non-blocking) or QUEUE (waits until available).
     * @param int                   $ttl            Time-to-live (in seconds) before auto-expiry (default: 300).
     * @param LoggerInterface|null  $logger         Optional PSR-3 logger instance.
     * @param string                $redisHost      Redis hostname (default: 127.0.0.1).
     * @param int                   $redisPort      Redis port (default: 6379).
     * @param string|null           $redisPassword  Optional Redis password.
     * @param int                   $redisDb        Redis database index (default: 0).
     */
    public function __construct(
        string $key,
        LockModeEnum $mode = LockModeEnum::EXECUTION,
        int $ttl = 300,
        ?LoggerInterface $logger = null,
        string $redisHost = '127.0.0.1',
        int $redisPort = 6379,
        ?string $redisPassword = null,
        int $redisDb = 0
    ) {
        $this->mode = $mode;
        $this->logger = $logger ?? LoggerFactory::create('lock/hybrid');

        // Try Redis first
        try {
            if ($this->canUseRedis($redisHost, $redisPort, $redisPassword, $redisDb)) {
                $this->driver = new RedisLockManager(
                    key: $key,
                    ttl: $ttl,
                    logger: $this->logger,
                    redisHost: $redisHost,
                    redisPort: $redisPort,
                    redisPassword: $redisPassword,
                    redisDb: $redisDb
                );
                $this->logger->info("HybridLock using Redis driver for {$key}");
                return;
            }
        } catch (Throwable $e) {
            $this->logger->warning('Redis unavailable, fallback to FileLockManager', [
                'error' => $e->getMessage(),
            ]);
        }

        // Fallback to file-based locking
        $lockFile = sys_get_temp_dir() . "/maatify/locks/{$key}.lock";
        $this->driver = new FileLockManager($lockFile, $ttl, $this->logger);
        $this->logger->info("HybridLock using File driver for {$key}");
    }

    // -----------------------------------------------------------
    // 🔹 Public Lock Interface
    // -----------------------------------------------------------

    /**
     * Attempt to acquire the lock immediately.
     *
     * @return bool True if the lock was acquired successfully, false otherwise.
     */
    public function acquire(): bool
    {
        return $this->driver->acquire();
    }

    /**
     * Check if the lock is currently active.
     *
     * @return bool True if locked, false otherwise.
     */
    public function isLocked(): bool
    {
        return $this->driver->isLocked();
    }

    /**
     * Release the lock manually.
     */
    public function release(): void
    {
        $this->driver->release();
    }

    // -----------------------------------------------------------
    // 🔹 Helper Methods for Queue Mode
    // -----------------------------------------------------------

    /**
     * Wait until the lock becomes available, then acquire it.
     *
     * @param int $retryDelay Microseconds between retries (default: 500,000 = 0.5s)
     */
    public function waitAndAcquire(int $retryDelay = 500_000): void
    {
        while (! $this->acquire()) {
            usleep($retryDelay);
        }
    }

    /**
     * Execute a callback under the protection of the lock.
     *
     * @param callable $callback     The code to run exclusively.
     * @param int      $retryDelay   Delay between retries in QUEUE mode (microseconds).
     */
    public function run(callable $callback, int $retryDelay = 500_000): void
    {
        if ($this->mode === LockModeEnum::QUEUE) {
            $this->waitAndAcquire($retryDelay);
        } elseif (! $this->acquire()) {
            return; // Skip silently if already locked
        }

        try {
            $callback();
        } finally {
            $this->release();
        }
    }

    // -----------------------------------------------------------
    // 🔹 Internal: Redis availability check
    // -----------------------------------------------------------

    /**
     * Checks whether Redis (phpredis or predis) is available and responsive.
     *
     * @param string      $host
     * @param int         $port
     * @param string|null $password
     * @param int         $db
     *
     * @return bool True if Redis connection and ping succeed.
     */
    private function canUseRedis(
        string $host,
        int $port,
        ?string $password,
        int $db
    ): bool {
        if (! class_exists(\Redis::class) && ! class_exists(\Predis\Client::class)) {
            return false;
        }

        try {
            if (class_exists(\Redis::class)) {
                $redis = new \Redis();
                if (! @$redis->connect($host, $port, 1.0)) {
                    return false;
                }
                if (!empty($password)) {
                    $redis->auth($password);
                }
                $redis->select($db);
                $redis->ping();
                return true;
            }

            if (class_exists(\Predis\Client::class)) {
                $client = new \Predis\Client([
                    'scheme' => 'tcp',
                    'host' => $host,
                    'port' => $port,
                    'password' => $password,
                    'database' => $db,
                ]);
                $client->ping();
                return true;
            }
        } catch (Throwable) {
            return false;
        }

        return false;
    }
}
