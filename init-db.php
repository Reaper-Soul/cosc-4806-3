<?php
// Create a SQLite database and the required tables

$db = new PDO("sqlite:" . __DIR__ . "/database.db");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Create users table
$db->exec("
    CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT NOT NULL UNIQUE,
        password TEXT NOT NULL
    );
");

// Create login_logs table
$db->exec("
    CREATE TABLE IF NOT EXISTS login_logs (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT,
        attempt TEXT,
        attempt_time DATETIME DEFAULT CURRENT_TIMESTAMP
    );
");

echo "Database and tables created successfully!";
