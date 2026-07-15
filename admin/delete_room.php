<?php
require_once("../includes/auth_check.php");
require_once("../config/db.php");

$stmt = $connection->prepare("DELETE FROM rooms WHERE id = ?");
$stmt->execute([$_GET['id'] ?? 0]);

header("Location: rooms.php");
exit;
