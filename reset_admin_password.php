<?php
require_once 'includes/db.php';

$password = 'admin123';
$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("UPDATE users SET password = ? WHERE username = 'admin'");
$stmt->execute([$hash]);

echo "Admin password reset to 'admin123'. Hash: " . $hash;
?>
