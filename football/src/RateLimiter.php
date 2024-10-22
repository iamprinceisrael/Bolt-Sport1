<?php
declare(strict_types=1);

require_once __DIR__ . '/Cache.php';

class RateLimiter {
    private const RATE_LIMIT = 60; // requests per minute
    private const RATE_LIMIT_WINDOW = 60; // seconds

    public static function checkRateLimit(string $ip): bool {
        $redis = Cache::getInstance();
        $key = "rate_limit:{$ip}";
        $current = $redis->get($key);

        if ($current === false) {
            $redis->setex($key, self::RATE_LIMIT_WINDOW, 1);
            return true;
        }

        if ($current >= self::RATE_LIMIT) {
            return false;
        }

        $redis->incr($key);
        return true;
    }

    public static function getRemainingRequests(string $ip): int {
        $redis = Cache::getInstance();
        $key = "rate_limit:{$ip}";
        $current = $redis->get($key);

        if ($current === false) {
            return self::RATE_LIMIT;
        }

        return max(0, self::RATE_LIMIT - (int)$current);
    }
}