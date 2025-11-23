<?php
require_once 'includes/db.php';

try {
    // Add email column if it doesn't exist
    $pdo->exec("ALTER TABLE users ADD COLUMN IF NOT EXISTS email VARCHAR(100) NOT NULL UNIQUE AFTER username");
    echo "Column 'email' added successfully.\n";

    // Update admin user
    $stmt = $pdo->prepare("UPDATE users SET email = ? WHERE username = 'admin'");
    $stmt->execute(['admin@example.com']);
    echo "Admin user updated with email 'admin@example.com'.\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>