<?php
declare(strict_types=1);

require_once __DIR__ . '/../models/Match.php';
require_once __DIR__ . '/../models/Team.php';
require_once __DIR__ . '/../PredictiveTool.php';

class MatchController {
    private Match $matchModel;
    private Team $teamModel;
    private PredictiveTool $predictiveTool;

    public function __construct() {
        $this->matchModel = new Match();
        $this->teamModel = new Team();
        $this->predictiveTool = new PredictiveTool();
    }

    public function index(): void {
        $matches = $this->matchModel->getAllMatches();
        echo json_encode($matches);
    }

    public function predict(string $matchId): void {
        if (!Security::validateUUID($matchId)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid match ID']);
            return;
        }

        $match = $this->matchModel->getMatchById($matchId);
        if (!$match) {
            http_response_code(404);
            echo json_encode(['error' => 'Match not found']);
            return;
        }

        $homeTeam = $this->teamModel->getTeamById($match['home_team_id']);
        $awayTeam = $this->teamModel->getTeamById($match['away_team_id']);

        $prediction = $this->predictiveTool->predictMatchOutcome($homeTeam, $awayTeam);

        echo json_encode([
            'match_id' => $matchId,
            'home_team' => $homeTeam['name'],
            'away_team' => $awayTeam['name'],
            'prediction' => $prediction,
        ]);
    }
}