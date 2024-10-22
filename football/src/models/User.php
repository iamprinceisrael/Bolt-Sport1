<?php
declare(strict_types=1);

require_once __DIR__ . '/../Database.php';

class User {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getUserByUsername(string $username): ?array {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function createUser(string $username, string $hashedPassword): void {
        $stmt = $this->db->prepare("INSERT INTO users (id, username, password) VALUES (UUID(), :username, :password)");
        $stmt->execute([
            'username' => $username,
            'password' => $hashedPassword
        ]);
    }
}