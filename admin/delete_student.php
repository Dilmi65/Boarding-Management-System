<?php
require_once("../includes/auth_check.php");
require_once("../config/db.php");

$stmt = $connection->prepare("DELETE FROM students WHERE id = ?");
$stmt->execute([$_GET['id'] ?? 0]);

header("Location: view_students.php");
exit;
