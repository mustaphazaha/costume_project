<?php
require_once 'includes/db.php';
try {
    $pdo->exec("DROP TABLE IF EXISTS users");
    echo "Users table dropped.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
