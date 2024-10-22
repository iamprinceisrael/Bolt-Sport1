<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../src/Database.php';
require_once __DIR__ . '/../src/Scraper.php';
require_once __DIR__ . '/../src/models/League.php';
require_once __DIR__ . '/../src/models/Team.php';
require_once __DIR__ . '/../src/models/Match.php';

$scraper = new Scraper();
$db = Database::getInstance();
$leagueModel = new League();
$teamModel = new Team();
$matchModel = new Match();

// Fetch and store leagues
$leagues = $scraper->fetchLeagues();
foreach ($leagues as $league) {
    $leagueModel->insertLeague($league);
    
    // Fetch and store teams for each league
    $teams = $scraper->fetchTeams($league['id']);
    foreach ($teams as $team) {
        $teamModel->insertTeam($team, $league['id']);
    }
    
    // Fetch and store matches for the 2020/2021 season
    $matches = $scraper->fetchMatches($league['id'], '2020/2021');
    foreach ($matches as $match) {
        $matchModel->insertMatch($match, $league['id']);
    }
}

echo "Scraping completed successfully.\n";