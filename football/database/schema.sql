CREATE DATABASE IF NOT EXISTS football_db;
USE football_db;

CREATE TABLE leagues (
    id CHAR(36) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    country VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE teams (
    id CHAR(36) PRIMARY KEY,
    league_id CHAR(36) NOT NULL,
    name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (league_id) REFERENCES leagues(id)
);

CREATE TABLE matches (
    id CHAR(36) PRIMARY KEY,
    league_id CHAR(36) NOT NULL,
    home_team_id CHAR(36) NOT NULL,
    away_team_id CHAR(36) NOT NULL,
    match_date DATETIME NOT NULL,
    home_score INT,
    away_score INT,
    status ENUM('scheduled', 'live', 'finished') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (league_id) REFERENCES leagues(id),
    FOREIGN KEY (home_team_id) REFERENCES teams(id),
    FOREIGN KEY (away_team_id) REFERENCES teams(id)
);

CREATE TABLE players (
    id CHAR(36) PRIMARY KEY,
    team_id CHAR(36) NOT NULL,
    name VARCHAR(255) NOT NULL,
    position VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (team_id) REFERENCES teams(id)
);

CREATE TABLE match_events (
    id CHAR(36) PRIMARY KEY,
    match_id CHAR(36) NOT NULL,
    player_id CHAR(36) NOT NULL,
    event_type ENUM('goal', 'assist', 'yellow_card', 'red_card', 'substitution') NOT NULL,
    minute INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (match_id) REFERENCES matches(id),
    FOREIGN KEY (player_id) REFERENCES players(id)
);

CREATE TABLE match_stats (
    id CHAR(36) PRIMARY KEY,
    match_id CHAR(36) NOT NULL,
    team_id CHAR(36) NOT NULL,
    possession FLOAT,
    shots INT,
    shots_on_target INT,
    corners INT,
    fouls INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (match_id) REFERENCES matches(id),
    FOREIGN KEY (team_id) REFERENCES teams(id)
);

CREATE TABLE users (
    id CHAR(36) PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);