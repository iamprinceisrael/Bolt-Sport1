<?php
declare(strict_types=1);

class Cache {
    private static ?Redis $instance = null;

    public static function getInstance(): Redis {
        if (self::$instance === null) {
            self::$instance = new Redis();
            self::$instance->connect(REDIS_HOST, REDIS_PORT);
        }
        return self::$instance;
    }

    public static function get(string $key) {
        $redis = self::getInstance();
        $value = $redis->get($key);
        return $value !== false ? json_decode($value, true) : null;
    }

    public static function set(string $key, $value, int $ttl = 3600): void {
        $redis = self::getInstance();
        $redis->setex($key, $ttl, json_encode($value));
    }
}