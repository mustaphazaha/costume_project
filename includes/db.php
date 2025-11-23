<?php
$host = 'localhost';
$dbname = 'costume_shop';
$username = 'root';
$password = '';

try {
    // Connect without DB first to avoid "Unknown database" if DSN parsing is weird
    $pdo = new PDO("mysql:host=$host;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if database exists before selecting
    $stmt = $pdo->query("SHOW DATABASES LIKE '$dbname'");
    if ($stmt->rowCount() == 0) {
        die("Erreur : La base de données '$dbname' n'existe pas. Veuillez importer le fichier database.sql.");
    }

    $pdo->exec("USE `$dbname`");
    
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
