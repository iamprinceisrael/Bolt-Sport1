<?php
declare(strict_types=1);

class Logger {
    private const LOG_FILE = __DIR__ . '/../logs/app.log';

    public static function log(string $message, string $level = 'INFO'): void {
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[$timestamp] [$level] $message" . PHP_EOL;
        file_put_contents(self::LOG_FILE, $logMessage, FILE_APPEND);
    }

    public static function error(string $message): void {
        self::log($message, 'ERROR');
    }

    public static function info(string $message): void {
        self::log($message, 'INFO');
    }
}