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

namespace Maatify\Common\Lock;

use Psr\Log\LoggerInterface;
use Maatify\PsrLogger\LoggerFactory;

/**
 * Class RedisLockManager
 *
 * A distributed lock manager that uses Redis to control concurrent access to resources.
 * Suitable for Cron jobs, background workers, or any process that must not run in parallel.
 *
 * 🧩 Features:
 * - Atomic locking via Redis `SET key value NX EX ttl`
 * - TTL auto-expiration (prevents stale locks)
 * - Works with both `phpredis` and `predis`
 * - Configurable Redis connection (host, port, password, DB)
 * - Fully PSR-3 compatible logging
 *
 * Example:
 * ```php
 * $lock = new RedisLockManager('cleanup', ttl: 600);
 * if (! $lock->acquire()) {
 *     echo "Another process is running.";
 *     exit;
 * }
 *
 * // Your process here...
 *
 * $lock->release();
 * ```
 */
final class RedisLockManager implements LockInterface
{
    private ?object $redis = null;
    private string $key;
    private int $ttl;
    private LoggerInterface $logger;

    /**
     * @param string                $key             The unique lock key (without prefix).
     * @param int                   $ttl             Time-to-live in seconds (default: 300).
     * @param LoggerInterface|null  $logger          Optional PSR-3 logger for debugging.
     * @param string                $redisHost       Redis hostname (default: 127.0.0.1).
     * @param int                   $redisPort       Redis port (default: 6379).
     * @param string|null           $redisPassword   Optional Redis password.
     * @param int                   $redisDb         Redis database index (default: 0).
     */
    public function __construct(
        string $key,
        int $ttl = 300,
        ?LoggerInterface $logger = null,
        string $redisHost = '127.0.0.1',
        int $redisPort = 6379,
        ?string $redisPassword = null,
        int $redisDb = 0
    ) {
        $this->key = "cron:lock:$key";
        $this->ttl = $ttl;
        $this->logger = $logger ?? LoggerFactory::create('cron/lock/redis');

        try {
            // Prefer phpredis extension
            if (class_exists(\Redis::class)) {
                $this->redis = new \Redis();
                $this->redis->connect($redisHost, $redisPort, 2.0);

                if (!empty($redisPassword)) {
                    $this->redis->auth($redisPassword);
                }

                $this->redis->select($redisDb);
            }
            // Fallback to predis client
            elseif (class_exists(\Predis\Client::class)) {
                $params = [
                    'scheme'   => 'tcp',
                    'host'     => $redisHost,
                    'port'     => $redisPort,
                    'database' => $redisDb,
                ];
                if (!empty($redisPassword)) {
                    $params['password'] = $redisPassword;
                }
                $this->redis = new \Predis\Client($params);
            } else {
                $this->logger->warning('No Redis client available (phpredis or predis)');
            }
        } catch (\Throwable $e) {
            $this->logger->error('Redis connection failed', [
                'error' => $e->getMessage(),
                'host'  => $redisHost,
                'port'  => $redisPort,
            ]);
            $this->redis = null;
        }
    }

    /**
     * Attempt to acquire the Redis lock.
     *
     * Uses an atomic `SET NX EX` command to ensure only one holder.
     * Returns false if a lock already exists.
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
            $this->logger->error('RedisLockManager acquire error', [
                'key' => $this->key,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Check whether the lock currently exists.
     *
     * @return bool True if the Redis key exists and has not expired.
     */
    public function isLocked(): bool
    {
        if ($this->redis === null) {
            return false;
        }

        try {
            return $this->redis->exists($this->key) === 1;
        } catch (\Throwable $e) {
            $this->logger->error('RedisLockManager isLocked error', [
                'key' => $this->key,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Release the lock manually by deleting the Redis key.
     *
     * Logs any errors but does not throw exceptions.
     */
    public function release(): void
    {
        if ($this->redis === null) {
            return;
        }

        try {
            $this->redis->del($this->key);
        } catch (\Throwable $e) {
            $this->logger->error('RedisLockManager release error', [
                'key' => $this->key,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
