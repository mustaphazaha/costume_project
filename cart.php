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
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Lato:wght@300;400;700&display=swap"
        rel="stylesheet">
</head>

<body class="bg-dark text-cream font-sans antialiased min-h-screen flex flex-col">

    <?php include 'includes/navbar.php'; ?>

    <main class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 w-full">
        <h1 class="text-4xl font-serif font-bold text-primary mb-8">Mon Panier</h1>

        <?php if (empty($cart_items)): ?>
            <div class="text-center py-16 bg-surface/30 rounded-xl border border-surface">
                <p class="text-xl text-secondary mb-6">Votre panier est vide.</p>
                <a href="index.php"
                    class="bg-primary text-dark font-bold py-3 px-8 rounded-lg hover:bg-secondary transition">Découvrir nos
                    costumes</a>
            </div>
        <?php else: ?>
            <div class="flex flex-col lg:flex-row gap-12">
                <!-- Cart Items -->
                <div class="flex-1 space-y-6">
                    <?php foreach ($cart_items as $item): ?>
                        <?php $itemPrice = $item['type'] === 'sale' ? $item['price'] : $item['rent_price']; ?>
                        <div
                            class="flex gap-6 p-4 bg-surface/30 rounded-xl border border-surface hover:border-primary/30 transition items-center">
                            <div class="w-24 h-32 flex-shrink-0 overflow-hidden rounded-lg">
                                <img src="<?php echo htmlspecialchars($item['image']); ?>"
                                    alt="<?php echo htmlspecialchars($item['name']); ?>" class="w-full h-full object-cover">
                            </div>

                            <div class="flex-1">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="text-xl font-bold text-cream"><?php echo htmlspecialchars($item['name']); ?>
                                        </h3>
                                        <p class="text-sm text-secondary uppercase tracking-wider mt-1">
                                            <?php echo $item['type'] === 'sale' ? 'Vente' : 'Location'; ?>
                                        </p>
                                    </div>
                                    <p class="text-xl font-bold text-primary"><?php echo number_format($itemPrice, 2); ?>€</p>
                                </div>

                                <div class="mt-4 flex items-center justify-between">
                                    <div class="flex items-center gap-4 text-sm text-gray-400">
                                        <span>Taille: <span
                                                class="text-cream"><?php echo htmlspecialchars($item['size']); ?></span></span>
                                        <span>Quantité: <span class="text-cream"><?php echo $item['quantity']; ?></span></span>
                                    </div>

                                    <button onclick="removeFromCart(<?php echo $item['cart_id']; ?>)"
                                        class="text-red-400 hover:text-red-300 text-sm font-medium transition flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path
                                                d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                            </path>
                                        </svg>
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

                        <button
                            class="w-full bg-primary text-dark font-bold py-4 rounded-xl hover:bg-secondary transition shadow-lg hover:shadow-xl">
                            Passer la commande
                        </button>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </main>

    <!-- Notification Container -->
    <div id="notification-container" class="fixed top-24 right-4 z-50 flex flex-col gap-4 pointer-events-none"></div>

    <!-- Confirmation Modal -->
    <div id="confirmation-modal" class="relative z-50 hidden" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div id="confirmation-backdrop" class="fixed inset-0 bg-black/80 backdrop-blur-sm transition-opacity opacity-0">
        </div>

        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div id="confirmation-panel"
                    class="relative transform overflow-hidden rounded-2xl bg-surface text-left shadow-2xl transition-all opacity-0 translate-y-4 scale-95 sm:w-full sm:max-w-lg border border-primary/20">
                    <div class="bg-surface px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-primary/20 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-xl font-serif font-bold leading-6 text-primary" id="modal-title">
                                    Confirmer la suppression</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-300">Voulez-vous vraiment retirer cet article de votre
                                        panier ?</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-dark/50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-white/5">
                        <button type="button" onclick="confirmRemoval()"
                            class="inline-flex w-full justify-center rounded-xl bg-red-600 px-3 py-2 text-sm font-bold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto transition-colors">Supprimer</button>
                        <button type="button" onclick="closeConfirmationModal()"
                            class="mt-3 inline-flex w-full justify-center rounded-xl bg-white/10 px-3 py-2 text-sm font-bold text-white shadow-sm hover:bg-white/20 sm:mt-0 sm:w-auto transition-colors">Annuler</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let itemToRemove = null;

        function removeFromCart(cartId) {
            itemToRemove = cartId;
            const modal = document.getElementById('confirmation-modal');
            const backdrop = document.getElementById('confirmation-backdrop');
            const panel = document.getElementById('confirmation-panel');

            if (modal && backdrop && panel) {
                modal.classList.remove('hidden');
                requestAnimationFrame(() => {
                    backdrop.classList.remove('opacity-0');
                    panel.classList.remove('opacity-0', 'translate-y-4', 'scale-95');
                });
            }
        }

        function closeConfirmationModal() {
            const modal = document.getElementById('confirmation-modal');
            const backdrop = document.getElementById('confirmation-backdrop');
            const panel = document.getElementById('confirmation-panel');

            if (backdrop && panel) {
                backdrop.classList.add('opacity-0');
                panel.classList.add('opacity-0', 'translate-y-4', 'scale-95');
                setTimeout(() => {
                    if (modal) modal.classList.add('hidden');
                    itemToRemove = null;
                }, 300);
            }
        }

        async function confirmRemoval() {
            if (!itemToRemove) return;

            const cartId = itemToRemove;
            closeConfirmationModal();

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
                    showNotification('Erreur: ' + data.message, 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Une erreur est survenue', 'error');
            }
        }

        // Notification System
        function showNotification(message, type = 'success') {
            const container = document.getElementById('notification-container');
            if (!container) return;

            // Create notification element
            const notification = document.createElement('div');
            notification.className = `
                transform transition-all duration-500 ease-out translate-x-full opacity-0
                flex items-center gap-3 px-6 py-4 rounded-xl shadow-2xl
                backdrop-blur-md border pointer-events-auto min-w-[300px]
                ${type === 'success'
                    ? 'bg-dark/90 border-primary/50 text-white'
                    : 'bg-red-900/90 border-red-500/50 text-white'}
            `;

            // Icon based on type
            const icon = type === 'success'
                ? `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>`
                : `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>`;

            notification.innerHTML = `
                ${icon}
                <div class="flex-1">
                    <h4 class="font-serif font-bold text-sm tracking-wide ${type === 'success' ? 'text-primary' : 'text-red-400'}">
                        ${type === 'success' ? 'Succès' : 'Erreur'}
                    </h4>
                    <p class="text-sm text-gray-200">${message}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="text-gray-400 hover:text-white transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            `;

            container.appendChild(notification);

            // Animate in
            requestAnimationFrame(() => {
                notification.classList.remove('translate-x-full', 'opacity-0');
            });

            // Auto remove after 3 seconds
            setTimeout(() => {
                notification.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => {
                    notification.remove();
                }, 500); // Wait for transition to finish
            }, 3000);
        }
    </script>
</body>

</html>