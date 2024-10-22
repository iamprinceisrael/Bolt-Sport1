<?php
declare(strict_types=1);

require_once __DIR__ . '/../Database.php';

class League {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAllLeagues(): array {
        $stmt = $this->db->query("SELECT * FROM leagues");
        return $stmt->fetchAll();
    }

    public function insertLeague(array $league): void {
        $stmt = $this->db->prepare("INSERT INTO leagues (id, name, country) VALUES (:id, :name, :country)");
        $stmt->execute([
            'id' => $league['id'],
            'name' => $league['name'],
            'country' => $league['country']
        ]);
    }
}