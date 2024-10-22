<?php
declare(strict_types=1);

require_once __DIR__ . '/../Database.php';

class Player {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAllPlayers(): array {
        $stmt = $this->db->query("SELECT * FROM players");
        return $stmt->fetchAll();
    }
}