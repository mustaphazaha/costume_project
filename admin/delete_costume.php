<?php
session_start();
require_once '../includes/db.php';

// Check if admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM costumes WHERE id = ?");
    $stmt->execute([$_GET['id']]);
}

header("Location: dashboard.php");
exit;
?>
