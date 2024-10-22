<?php
declare(strict_types=1);

require_once __DIR__ . '/../models/Team.php';

class TeamController {
    public function index(): void {
        $teamModel = new Team();
        $teams = $teamModel->getAllTeams();
        echo json_encode($teams);
    }
}