<?php
declare(strict_types=1);

require_once __DIR__ . '/../Database.php';

class Match {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAllMatches(): array {
        $stmt = $this->db->query("SELECT * FROM matches");
        return $stmt->fetchAll();
    }

    public function getMatchById(string $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM matches WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $match = $stmt->fetch();
        return $match ?: null;
    }

    public function insertMatch(array $match, string $leagueId): void {
        $stmt = $this->db->prepare("INSERT INTO matches (id, league_id, home_team_id, away_team_id, match_date, home_score, away_score, status) VALUES (:id, :league_id, :home_team_id, :away_team_id, :match_date, :home_score, :away_score, :status)");
        $stmt->execute([
            'id' => $match['id'],
            'league_id' => $leagueId,
            'home_team_id' => $match['homeTeam']['id'],
            'away_team_id' => $match['awayTeam']['id'],
            'match_date' => $match['date'],
            'home_score' => $match['homeScore']['current'] ?? null,
            'away_score' => $match['awayScore']['current'] ?? null,
            'status' => $match['status']['type']
        ]);
    }
}