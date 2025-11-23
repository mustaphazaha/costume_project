<?php
session_start();
require_once 'includes/db.php';

echo "--- Verifying Admin Costume ---\n";
$stmt = $pdo->query("SELECT * FROM costumes WHERE name = 'Test Costume'");
$costume = $stmt->fetch(PDO::FETCH_ASSOC);
if ($costume) {
    echo "SUCCESS: Test Costume found in DB. ID: " . $costume['id'] . "\n";
} else {
    echo "FAILURE: Test Costume NOT found in DB.\n";
}

echo "\n--- Verifying User Cart ---\n";
// Simulate User Login
$stmt = $pdo->prepare("SELECT id FROM users WHERE username = 'user'");
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) {
    die("FAILURE: Test user not found.\n");
}
$user_id = $user['id'];
$_SESSION['user_id'] = $user_id;
echo "Logged in as user ID: $user_id\n";

// Add item to cart (simulate API)
$costume_id = $costume ? $costume['id'] : 1; // Use test costume or ID 1
$quantity = 1;
$size = 'M';

// Check if exists first to avoid duplicates from previous runs
$stmt = $pdo->prepare("DELETE FROM cart_items WHERE user_id = ?");
$stmt->execute([$user_id]);

$stmt = $pdo->prepare("INSERT INTO cart_items (user_id, costume_id, size, quantity) VALUES (?, ?, ?, ?)");
if ($stmt->execute([$user_id, $costume_id, $size, $quantity])) {
    echo "SUCCESS: Item added to cart table.\n";
} else {
    echo "FAILURE: Could not add item to cart.\n";
}

// Fetch cart
$stmt = $pdo->prepare("
    SELECT c.id as cart_id, co.name 
    FROM cart_items c 
    JOIN costumes co ON c.costume_id = co.id 
    WHERE c.user_id = ?
");
$stmt->execute([$user_id]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($items) > 0) {
    echo "SUCCESS: Cart contains " . count($items) . " item(s).\n";
    print_r($items);
} else {
    echo "FAILURE: Cart is empty.\n";
}
?>
