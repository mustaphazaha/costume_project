<?php
session_start();
require_once '../includes/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];
$action = $_POST['action'] ?? $_GET['action'] ?? '';

try {
    if ($action === 'add') {
        $costume_id = $_POST['costume_id'];
        $size = $_POST['size'] ?? null;
        $quantity = 1;

        // Check if item already exists in cart
        $stmt = $pdo->prepare("SELECT id, quantity FROM cart_items WHERE user_id = ? AND costume_id = ? AND size = ?");
        $stmt->execute([$user_id, $costume_id, $size]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            $new_qty = $existing['quantity'] + 1;
            $stmt = $pdo->prepare("UPDATE cart_items SET quantity = ? WHERE id = ?");
            $stmt->execute([$new_qty, $existing['id']]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO cart_items (user_id, costume_id, size, quantity) VALUES (?, ?, ?, ?)");
            $stmt->execute([$user_id, $costume_id, $size, $quantity]);
        }

        echo json_encode(['success' => true, 'message' => 'Item added to cart']);

    } elseif ($action === 'remove') {
        $cart_id = $_POST['cart_id'];
        $stmt = $pdo->prepare("DELETE FROM cart_items WHERE id = ? AND user_id = ?");
        $stmt->execute([$cart_id, $user_id]);
        echo json_encode(['success' => true, 'message' => 'Item removed']);

    } elseif ($action === 'get') {
        $stmt = $pdo->prepare("
            SELECT c.id as cart_id, c.quantity, c.size, co.name, co.price, co.rent_price, co.image, co.type 
            FROM cart_items c 
            JOIN costumes co ON c.costume_id = co.id 
            WHERE c.user_id = ?
        ");
        $stmt->execute([$user_id]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode(['success' => true, 'items' => $items]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
