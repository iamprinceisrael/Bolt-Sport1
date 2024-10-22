<?php
declare(strict_types=1);

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/src/Router.php';
require_once __DIR__ . '/src/Database.php';
require_once __DIR__ . '/src/Cache.php';
require_once __DIR__ . '/src/Security.php';
require_once __DIR__ . '/src/Scraper.php';
require_once __DIR__ . '/src/Logger.php';

// Error handling
set_exception_handler(function (Throwable $e) {
    Logger::error($e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Internal Server Error']);
});

// Initialize the router
$router = new Router();

// Define routes
$router->addRoute('GET', '/', 'HomeController@index');
$router->addRoute('POST', '/api/auth/login', 'AuthController@login');
$router->addRoute('POST', '/api/auth/register', 'AuthController@register');
$router->addRoute('GET', '/api/leagues', 'LeagueController@index', true);
$router->addRoute('GET', '/api/teams', 'TeamController@index', true);
$router->addRoute('GET', '/api/matches', 'MatchController@index', true);
$router->addRoute('GET', '/api/matches/:id/predict', 'MatchController@predict', true);
$router->addRoute('GET', '/api/players', 'PlayerController@index', true);
$router->addRoute('GET', '/api/stats', 'StatController@index', true);

try {
    // Handle the request
    $router->handleRequest();
} catch (Exception $e) {
    Logger::error($e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Internal Server Error']);
}