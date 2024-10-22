<?php
declare(strict_types=1);

require_once __DIR__ . '/../models/Player.php';

class PlayerController {
    public function index(): void {
        $playerModel = new Player();
        $players = $playerModel->getAllPlayers();
        echo json_encode($players);
    }
}