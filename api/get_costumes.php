<?php
require_once '../includes/db.php';

header('Content-Type: application/json');

try {
    $stmt = $pdo->query("SELECT * FROM costumes");
    $costumes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Transform data to match frontend expectations
    foreach ($costumes as &$costume) {
        // Ensure 'available' is boolean
        $costume['available'] = (bool)$costume['available'];
        
        // Handle images (stored as single string in DB, frontend expects array)
        $costume['images'] = [$costume['image']]; 
        
        // Handle sizes and colors (stored as JSON string in DB)
        $costume['size'] = json_decode($costume['size']) ?? [];
        $costume['color'] = json_decode($costume['color']) ?? [];
    }

    echo json_encode($costumes);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
