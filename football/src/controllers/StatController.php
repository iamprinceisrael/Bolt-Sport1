<?php
declare(strict_types=1);

require_once __DIR__ . '/../models/Stat.php';

class StatController {
    public function index(): void {
        $statModel = new Stat();
        $stats = $statModel->getAllStats();
        echo json_encode($stats);
    }
}