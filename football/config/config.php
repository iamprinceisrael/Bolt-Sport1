<?php
declare(strict_types=1);

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'football_db');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');

// Redis configuration
define('REDIS_HOST', 'localhost');
define('REDIS_PORT', 6379);

// API settings
define('API_BASE_URL', '/api');

// Security settings
define('JWT_SECRET', 'your_jwt_secret_key');
define('BCRYPT_COST', 12);

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Set default timezone
date_default_timezone_set('UTC');