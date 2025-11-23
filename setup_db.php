<?php
$host = 'localhost';
$username = 'root';
$password = '';

try {
    // Connect to MySQL without database selected
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create Database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS costume_shop");
    echo "Database 'costume_shop' created or already exists.<br>";

    // Select Database
    $pdo->exec("USE costume_shop");

    // Create Users Table
    $sqlUsers = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role ENUM('user', 'admin') DEFAULT 'user',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sqlUsers);
    
    // Check if email column exists
    $stmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'email'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE users ADD COLUMN email VARCHAR(100) NOT NULL UNIQUE AFTER username");
        echo "Column 'email' added to users table.<br>";
    }

    // Remove UNIQUE constraint from username if it exists
    // This is a bit tricky in MySQL as we need the index name. Usually it's 'username'.
    try {
        $pdo->exec("ALTER TABLE users DROP INDEX username");
        echo "Unique constraint on 'username' removed.<br>";
    } catch (PDOException $e) {
        // Index might not exist or have a different name. 
        // If it fails, it's likely already removed or not there.
    }

    echo "Table 'users' schema updated.<br>";

    // Create Costumes Table
    $sqlCostumes = "CREATE TABLE IF NOT EXISTS costumes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        description TEXT,
        price DECIMAL(10, 2) NOT NULL,
        rent_price DECIMAL(10, 2) NOT NULL,
        image VARCHAR(255),
        category VARCHAR(50),
        type ENUM('sale', 'rent') NOT NULL,
        size VARCHAR(255),
        color VARCHAR(255),
        available BOOLEAN DEFAULT TRUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sqlCostumes);
    echo "Table 'costumes' created.<br>";

    // Create Cart Items Table
    $sqlCart = "CREATE TABLE IF NOT EXISTS cart_items (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        costume_id INT NOT NULL,
        quantity INT DEFAULT 1,
        size VARCHAR(10),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (costume_id) REFERENCES costumes(id) ON DELETE CASCADE
    )";
    $pdo->exec($sqlCart);
    echo "Table 'cart_items' created.<br>";

    // Check if admin exists
    $stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE username = 'admin'");
    if ($stmt->fetchColumn() == 0) {
        // Insert Admin User (password: admin123)
        $passwordHash = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES ('admin', 'admin@example.com', ?, 'admin')");
        $stmt->execute([$passwordHash]);
        echo "Admin user created.<br>";
    }

    // Check if costumes exist
    $stmt = $pdo->query("SELECT COUNT(*) FROM costumes");
    if ($stmt->fetchColumn() == 0) {
        // Insert Sample Costumes
        $sqlInsert = "INSERT INTO costumes (name, description, price, rent_price, image, category, type, size, color, available) VALUES
        ('Costume 3 Pièces Bleu Nuit', 'Un costume élégant pour les grandes occasions.', 299.99, 45.00, 'https://images.unsplash.com/photo-1594938298603-c8148c472f29?q=80&w=1000&auto=format&fit=crop', 'Classique', 'sale', '[\"M\", \"L\", \"XL\"]', '[\"Bleu\"]', 1),
        ('Smoking Noir Satin', 'Le classique indémodable pour vos soirées de gala.', 350.00, 60.00, 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?q=80&w=1000&auto=format&fit=crop', 'Soirée', 'rent', '[\"S\", \"M\", \"L\"]', '[\"Noir\"]', 1),
        ('Veste Tweed Vintage', 'Un style british authentique pour un look distingué.', 180.00, 30.00, 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?q=80&w=1000&auto=format&fit=crop', 'Vintage', 'sale', '[\"L\", \"XL\"]', '[\"Marron\", \"Beige\"]', 1),
        ('Costume Lin Beige', 'Léger et respirant, parfait pour les mariages d\'été.', 220.00, 40.00, 'https://images.unsplash.com/photo-1487222477894-8943e31ef7b2?q=80&w=1000&auto=format&fit=crop', 'Moderne', 'rent', '[\"M\", \"L\"]', '[\"Beige\"]', 1),
        ('Ensemble Bordeaux', 'Osez la couleur avec cet ensemble moderne et audacieux.', 280.00, 50.00, 'https://images.unsplash.com/photo-1552374196-1ab2a1c593e8?q=80&w=1000&auto=format&fit=crop', 'Moderne', 'sale', '[\"S\", \"M\"]', '[\"Autre\"]', 1),
        ('Queue de Pie Classique', 'La tenue de cérémonie par excellence.', 400.00, 80.00, 'https://images.unsplash.com/photo-1559582798-678dfc71ccd8?q=80&w=1000&auto=format&fit=crop', 'Soirée', 'rent', '[\"M\", \"L\", \"XL\"]', '[\"Noir\", \"Blanc\"]', 1)";
        $pdo->exec($sqlInsert);
        echo "Sample costumes inserted.<br>";
    }

    echo "Setup completed successfully!<br>";
    
    echo "<h3>Databases:</h3>";
    $dbs = $pdo->query("SHOW DATABASES")->fetchAll(PDO::FETCH_COLUMN);
    echo implode(", ", $dbs) . "<br>";

    echo "<h3>Tables in costume_shop:</h3>";
    $tables = $pdo->query("SHOW TABLES FROM costume_shop")->fetchAll(PDO::FETCH_COLUMN);
    echo implode(", ", $tables) . "<br>";

} catch (PDOException $e) {
    die("DB Error: " . $e->getMessage());
}
?>
