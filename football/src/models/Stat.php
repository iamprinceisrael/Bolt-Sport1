<?php
declare(strict_types=1);

require_once __DIR__ . '/../Database.php';

class Stat {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAllStats(): array {
        $stmt = $this->db->query("SELECT * FROM match_stats");
        return $stmt->fetchAll();
    }
}