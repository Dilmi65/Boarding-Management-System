<?php
require_once("../includes/auth_check.php");
require_once("../config/db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $connection->prepare(
        "INSERT INTO rooms (room_number, capacity, rent_price, status) VALUES (?, ?, ?, ?)"
    );
    $stmt->execute([
        trim($_POST['room_number']),
        (int) $_POST['capacity'],
        (float) $_POST['rent_price'],
        $_POST['status'],
    ]);
}

header("Location: rooms.php");
exit;
