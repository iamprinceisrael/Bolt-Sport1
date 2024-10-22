<?php
declare(strict_types=1);

class HomeController {
    public function index(): void {
        echo json_encode(['message' => 'Welcome to the Football API']);
    }
}