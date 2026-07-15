<?php
session_start();
require_once("../config/db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentId = trim($_POST['student_id'] ?? '');
    $password  = $_POST['password'] ?? '';

    $stmt = $connection->prepare("SELECT * FROM students WHERE student_id = ? LIMIT 1");
    $stmt->execute([$studentId]);
    $student = $stmt->fetch();

    if ($student && password_verify($password, $student['password'])) {
        session_regenerate_id(true);
        $_SESSION['role']         = 'student';
        $_SESSION['student_db_id'] = $student['id'];
        $_SESSION['student_name']  = $student['name'];
        header("Location: dashboard.php");
        exit;
    } else {
        $_SESSION['login_error'] = "Invalid Student ID or password.";
        header("Location: login.php");
        exit;
    }
}

header("Location: login.php");
exit;
