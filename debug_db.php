<?php
require_once 'includes/db.php';

echo "--- Users Table Columns ---\n";
$stmt = $pdo->query("SHOW COLUMNS FROM users");
$columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($columns as $col) {
    echo $col['Field'] . " (" . $col['Type'] . ")\n";
}

echo "\n--- Admin User Data ---\n";
$stmt = $pdo->query("SELECT id, username, email, role FROM users WHERE username = 'admin'");
$admin = $stmt->fetch(PDO::FETCH_ASSOC);
print_r($admin);

echo "\n--- Test User Data ---\n";
$stmt = $pdo->query("SELECT id, username, email, role FROM users WHERE username = 'testuser'");
$testuser = $stmt->fetch(PDO::FETCH_ASSOC);
print_r($testuser);
?>
