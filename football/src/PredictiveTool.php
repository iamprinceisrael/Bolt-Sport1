<?php
declare(strict_types=1);

class PredictiveTool {
    private const K_FACTOR = 32;
    private const HOME_ADVANTAGE = 100;

    public function predictMatchOutcome(array $homeTeam, array $awayTeam, int $simulations = 10000): array {
        $homeWins = 0;
        $draws = 0;
        $awayWins = 0;

        for ($i = 0; $i < $simulations; $i++) {
            $result = $this->simulateMatch($homeTeam, $awayTeam);
            if ($result > 0) {
                $homeWins++;
            } elseif ($result < 0) {
                $awayWins++;
            } else {
                $draws++;
            }
        }

        return [
            'home_win_probability' => $homeWins / $simulations,
            'draw_probability' => $draws / $simulations,
            'away_win_probability' => $awayWins / $simulations,
        ];
    }

    private function simulateMatch(array $homeTeam, array $awayTeam): int {
        $homeRating = $homeTeam['rating'] + self::HOME_ADVANTAGE;
        $awayRating = $awayTeam['rating'];

        $homeExpected = 1 / (1 + pow(10, ($awayRating - $homeRating) / 400));
        $awayExpected = 1 - $homeExpected;

        $randomValue = mt_rand() / mt_getrandmax();

        if ($randomValue < $homeExpected) {
            return 1; // Home win
        } elseif ($randomValue > $homeExpected + $awayExpected) {
            return -1; // Away win
        } else {
            return 0; // Draw
        }
    }

    public function updateEloRatings(array &$homeTeam, array &$awayTeam, float $homeScore, float $awayScore): void {
        $homeRating = $homeTeam['rating'] + self::HOME_ADVANTAGE;
        $awayRating = $awayTeam['rating'];

        $homeExpected = 1 / (1 + pow(10, ($awayRating - $homeRating) / 400));
        $awayExpected = 1 - $homeExpected;

        $homeActual = ($homeScore > $awayScore) ? 1 : (($homeScore < $awayScore) ? 0 : 0.5);
        $awayActual = 1 - $homeActual;

        $homeTeam['rating'] += self::K_FACTOR * ($homeActual - $homeExpected);
        $awayTeam['rating'] += self::K_FACTOR * ($awayActual - $awayExpected);
    }
}