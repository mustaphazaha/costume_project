<?php
require_once 'includes/db.php';

$username = 'user';
$password = 'user123';
$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT IGNORE INTO users (username, password, role) VALUES (?, ?, 'user')");
$stmt->execute([$username, $hash]);

echo "Test user created (user/user123)";
?>
