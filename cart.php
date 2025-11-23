<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch cart items
$stmt = $pdo->prepare("
    SELECT c.id as cart_id, c.quantity, c.size, co.name, co.price, co.rent_price, co.image, co.type 
    FROM cart_items c 
    JOIN costumes co ON c.costume_id = co.id 
    WHERE c.user_id = ?
");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total = 0;
foreach ($cart_items as $item) {
    $price = $item['type'] === 'sale' ? $item['price'] : $item['rent_price'];
    $total += $price * $item['quantity'];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Panier - Costume Shop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#b4a078',
                        secondary: '#c8b48c',
                        dark: '#141414',
                        surface: '#282828',
                        sage: '#b4b48c',
                        cream: '#f0efeb',
                        charcoal: '#141414',
                    },
                    fontFamily: {
                        serif: ['Playfair Display', 'serif'],
                        sans: ['Lato', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
</head>
<body class="bg-dark text-cream font-sans antialiased min-h-screen flex flex-col">

    <?php include 'includes/navbar.php'; ?>

    <main class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 w-full">
        <h1 class="text-4xl font-serif font-bold text-primary mb-8">Mon Panier</h1>

        <?php if (empty($cart_items)): ?>
            <div class="text-center py-16 bg-surface/30 rounded-xl border border-surface">
                <p class="text-xl text-secondary mb-6">Votre panier est vide.</p>
                <a href="index.php" class="bg-primary text-dark font-bold py-3 px-8 rounded-lg hover:bg-secondary transition">Découvrir nos costumes</a>
            </div>
        <?php else: ?>
            <div class="flex flex-col lg:flex-row gap-12">
                <!-- Cart Items -->
                <div class="flex-1 space-y-6">
                    <?php foreach ($cart_items as $item): ?>
                        <?php $itemPrice = $item['type'] === 'sale' ? $item['price'] : $item['rent_price']; ?>
                        <div class="flex gap-6 p-4 bg-surface/30 rounded-xl border border-surface hover:border-primary/30 transition items-center">
                            <div class="w-24 h-32 flex-shrink-0 overflow-hidden rounded-lg">
                                <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="w-full h-full object-cover">
                            </div>
                            
                            <div class="flex-1">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="text-xl font-bold text-cream"><?php echo htmlspecialchars($item['name']); ?></h3>
                                        <p class="text-sm text-secondary uppercase tracking-wider mt-1"><?php echo $item['type'] === 'sale' ? 'Vente' : 'Location'; ?></p>
                                    </div>
                                    <p class="text-xl font-bold text-primary"><?php echo number_format($itemPrice, 2); ?>€</p>
                                </div>
                                
                                <div class="mt-4 flex items-center justify-between">
                                    <div class="flex items-center gap-4 text-sm text-gray-400">
                                        <span>Taille: <span class="text-cream"><?php echo htmlspecialchars($item['size']); ?></span></span>
                                        <span>Quantité: <span class="text-cream"><?php echo $item['quantity']; ?></span></span>
                                    </div>
                                    
                                    <button onclick="removeFromCart(<?php echo $item['cart_id']; ?>)" class="text-red-400 hover:text-red-300 text-sm font-medium transition flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                        Supprimer
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Summary -->
                <div class="w-full lg:w-96 flex-shrink-0">
                    <div class="bg-surface/50 p-6 rounded-xl border border-surface sticky top-24">
                        <h2 class="text-xl font-bold text-primary mb-6">Résumé</h2>
                        
                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between text-cream">
                                <span>Sous-total</span>
                                <span><?php echo number_format($total, 2); ?>€</span>
                            </div>
                            <div class="flex justify-between text-secondary text-sm">
                                <span>Livraison</span>
                                <span>Calculé à l'étape suivante</span>
                            </div>
                            <div class="border-t border-white/10 pt-4 flex justify-between text-xl font-bold text-primary">
                                <span>Total</span>
                                <span><?php echo number_format($total, 2); ?>€</span>
                            </div>
                        </div>

                        <button class="w-full bg-primary text-dark font-bold py-4 rounded-xl hover:bg-secondary transition shadow-lg hover:shadow-xl">
                            Passer la commande
                        </button>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </main>

    <script>
        async function removeFromCart(cartId) {
            if (!confirm('Voulez-vous vraiment retirer cet article ?')) return;

            const formData = new FormData();
            formData.append('action', 'remove');
            formData.append('cart_id', cartId);

            try {
                const response = await fetch('api/cart.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();
                
                if (data.success) {
                    location.reload();
                } else {
                    alert('Erreur: ' + data.message);
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }
    </script>
</body>
</html>
