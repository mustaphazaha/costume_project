<?php
require_once 'includes/db.php';

$username = 'admin';
$password = 'admin123';

$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    echo "User found: " . $user['username'] . "\n";
    echo "Email: " . $user['email'] . "\n";
    if (password_verify($password, $user['password'])) {
        echo "Password '$password' is CORRECT.\n";
    } else {
        echo "Password '$password' is INCORRECT.\n";
    }
} else {
    echo "User '$username' not found.\n";
}
?>