<?php
declare(strict_types=1);

class Scraper {
    private string $baseUrl = 'https://api.sofascore.com/api/v1';

    public function fetchLeagues(): array {
        $url = $this->baseUrl . '/football/leagues';
        return $this->makeRequest($url);
    }

    public function fetchTeams(string $leagueId): array {
        $url = $this->baseUrl . "/football/league/{$leagueId}/teams";
        return $this->makeRequest($url);
    }

    public function fetchMatches(string $leagueId, string $season): array {
        $url = $this->baseUrl . "/football/league/{$leagueId}/season/{$season}/matches";
        return $this->makeRequest($url);
    }

    private function makeRequest(string $url): array {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            throw new Exception("API request failed with status code: {$httpCode}");
        }

        return json_decode($response, true);
    }
}