<?php

class User
{
    private $db;

    public function __construct()
    {
        // Path assumes index.php is in public/, and database.db is in project root
        $this->db = new PDO("sqlite:" . dirname(__DIR__, 2) . "/database.db");
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findUser($username)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createUser($username, $password)
    {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        return $stmt->execute([$username, $hashed]);
    }

    public function logAttempt($username, $status)
    {
        $stmt = $this->db->prepare("INSERT INTO login_logs (username, attempt) VALUES (?, ?)");
        $stmt->execute([$username, $status]);
    }

    public function failedAttempts($username)
    {
        $stmt = $this->db->prepare("SELECT * FROM login_logs WHERE username = ? AND attempt = 'failed' ORDER BY attempt_time DESC LIMIT 3");
        $stmt->execute([$username]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
