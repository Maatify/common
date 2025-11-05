<?php
/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-11-05
 * Time: 22:29
 * Project: maatify:common
 * IDE: PhpStorm
 * https://www.Maatify.dev
 */

declare(strict_types=1);

namespace Maatify\Common\Cron;

use Psr\Log\LoggerInterface;
use Maatify\PsrLogger\LoggerFactory;

/**
 * Class RedisCronLock
 *
 * Implements a Redis-based distributed lock for Cron jobs.
 * Ensures only one instance of a scheduled task runs across multiple servers.
 *
 * 🧩 Features:
 * - Uses Redis `SET key value NX EX ttl` command for atomic locking.
 * - Works with either `phpredis` or `predis`.
 * - Automatically expires after a configurable TTL (default: 5 minutes).
 * - Logs errors and warnings through PSR-3.
 *
 * Example:
 * ```php
 * $lock = new RedisCronLock('daily_cleanup', ttl: 600);
 * if (! $lock->acquire()) {
 *     echo "Job already running. Exiting.";
 *     exit;
 * }
 *
 * // ... job logic ...
 *
 * $lock->release();
 * ```
 */
final class RedisCronLock implements CronLockInterface
{
    private ?object $redis = null;
    private string $key;
    private int $ttl;
    private LoggerInterface $logger;

    /**
     * @param string                $key      Lock key name (without prefix).
     * @param int                   $ttl      Lock time-to-live in seconds (default 300 s).
     * @param LoggerInterface|null  $logger   Optional PSR-3 logger.
     */
    public function __construct(string $key, int $ttl = 300, ?LoggerInterface $logger = null)
    {
        $this->key = "cron:lock:$key";
        $this->ttl = $ttl;
        $this->logger = $logger ?? LoggerFactory::create('cron/lock/redis');

        // ✅ Try connecting using phpredis or predis
        if (class_exists(\Redis::class)) {
            $this->redis = new \Redis();
            try {
                $this->redis->connect('127.0.0.1', 6379);
            } catch (\Throwable $e) {
                $this->logger->warning('Cannot connect to Redis server', [
                    'error' => $e->getMessage(),
                ]);
                $this->redis = null;
            }
        } elseif (class_exists(\Predis\Client::class)) {
            $this->redis = new \Predis\Client();
        } else {
            $this->logger->warning('Neither phpredis nor predis is available — RedisCronLock disabled.');
        }
    }

    /**
     * Attempt to acquire the Redis lock.
     *
     * Uses an atomic `SET NX EX` command. Returns `false` if the key already exists.
     *
     * @return bool True if the lock was acquired successfully, false otherwise.
     */
    public function acquire(): bool
    {
        if ($this->redis === null) {
            return false;
        }

        try {
            return $this->redis->set($this->key, (string) time(), ['nx', 'ex' => $this->ttl]) !== false;
        } catch (\Throwable $e) {
            $this->logger->error('RedisCronLock acquire error', [
                'key' => $this->key,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Check whether the Redis lock key currently exists and is valid.
     *
     * @return bool True if the lock key exists, false otherwise.
     */
    public function isLocked(): bool
    {
        if ($this->redis === null) {
            return false;
        }

        try {
            return $this->redis->exists($this->key) === 1;
        } catch (\Throwable $e) {
            $this->logger->error('RedisCronLock isLocked error', [
                'key' => $this->key,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Release the lock manually by deleting the Redis key.
     *
     * Any exceptions are logged but never thrown.
     */
    public function release(): void
    {
        if ($this->redis === null) {
            return;
        }

        try {
            $this->redis->del($this->key);
        } catch (\Throwable $e) {
            $this->logger->error('RedisCronLock release error', [
                'key' => $this->key,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
