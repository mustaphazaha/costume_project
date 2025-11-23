<?php
require_once 'includes/db.php';

// 1. Ensure email column exists
try {
    $pdo->exec("ALTER TABLE users ADD COLUMN email VARCHAR(100) NOT NULL UNIQUE AFTER username");
    echo "Column 'email' added.\n";
} catch (PDOException $e) {
    echo "Column 'email' likely exists.\n";
}

// 2. Fix Admin
$pass = password_hash('admin123', PASSWORD_DEFAULT);
$stmt = $pdo->prepare("UPDATE users SET email = 'admin@example.com', password = ? WHERE username = 'admin'");
$stmt->execute([$pass]);
if ($stmt->rowCount() > 0) {
    echo "Admin updated.\n";
} else {
    // Insert if not exists
    $stmt = $pdo->prepare("INSERT IGNORE INTO users (username, email, password, role) VALUES ('admin', 'admin@example.com', ?, 'admin')");
    $stmt->execute([$pass]);
    echo "Admin inserted/checked.\n";
}

// 3. Fix User
$pass = password_hash('user123', PASSWORD_DEFAULT);
$stmt = $pdo->prepare("DELETE FROM users WHERE username = 'user'");
$stmt->execute();

$stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES ('user', 'user@example.com', ?, 'user')");
$stmt->execute([$pass]);
echo "User 'user' created with email 'user@example.com'.\n";

?>
