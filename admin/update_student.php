<?php
require_once("../includes/auth_check.php");
require_once("../config/db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $roomId = $_POST['room_id'] !== '' ? (int) $_POST['room_id'] : null;

    if (!empty($_POST['password'])) {
        $stmt = $connection->prepare(
            "UPDATE students SET name = ?, email = ?, phone = ?, room_id = ?, student_id = ?, password = ? WHERE id = ?"
        );
        $stmt->execute([
            trim($_POST['name']),
            trim($_POST['email']),
            trim($_POST['phone']),
            $roomId,
            trim($_POST['sid']),
            password_hash($_POST['password'], PASSWORD_DEFAULT),
            (int) $_POST['id'],
        ]);
    } else {
        $stmt = $connection->prepare(
            "UPDATE students SET name = ?, email = ?, phone = ?, room_id = ?, student_id = ? WHERE id = ?"
        );
        $stmt->execute([
            trim($_POST['name']),
            trim($_POST['email']),
            trim($_POST['phone']),
            $roomId,
            trim($_POST['sid']),
            (int) $_POST['id'],
        ]);
    }
}

header("Location: view_students.php");
exit;
