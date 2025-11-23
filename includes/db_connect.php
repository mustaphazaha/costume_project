<?php
$host = 'localhost';
$dbname = 'costume_shop';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // DEBUG: List databases
    $stmt = $pdo->query("SHOW DATABASES");
    $dbs = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (!in_array($dbname, $dbs)) {
        die("DEBUG: Database '$dbname' not found. Available: " . implode(", ", $dbs));
    }

    $pdo->exec("USE `$dbname`");
    
} catch (PDOException $e) {
    die("MY_UNIQUE_ERROR: " . $e->getMessage());
}
?>
