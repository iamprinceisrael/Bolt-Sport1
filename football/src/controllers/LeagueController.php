<?php
declare(strict_types=1);

require_once __DIR__ . '/../models/League.php';

class LeagueController {
    public function index(): void {
        $leagueModel = new League();
        $leagues = $leagueModel->getAllLeagues();
        echo json_encode($leagues);
    }
}