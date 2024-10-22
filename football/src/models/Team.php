<?php
declare(strict_types=1);

require_once __DIR__ . '/../Database.php';

class Team {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAllTeams(): array {
        $stmt = $this->db->query("SELECT * FROM teams");
        return $stmt->fetchAll();
    }

    public function getTeamById(string $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM teams WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $team = $stmt->fetch();
        return $team ?: null;
    }

    public function insertTeam(array $team, string $leagueId): void {
        $stmt = $this->db->prepare("INSERT INTO teams (id, league_id, name) VALUES (:id, :league_id, :name)");
        $stmt->execute([
            'id' => $team['id'],
            'league_id' => $leagueId,
            'name' => $team['name']
        ]);
    }
}