<?php
/**
 * Database connection (PDO)
 * Update the credentials below to match your MySQL setup.
 */

$db_host = "localhost:4307";
$db_name = "boarding_system";
$db_user = "root";
$db_pass = "";

try {
    $connection = new PDO(
        "mysql:host={$db_host};dbname={$db_name};charset=utf8mb4",
        $db_user,
        $db_pass,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    die("Database connection failed: " . htmlspecialchars($e->getMessage()));
}
