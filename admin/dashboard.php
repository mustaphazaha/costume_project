<?php
session_start();
require_once '../includes/db.php';

// Check if admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Fetch all costumes
$stmt = $pdo->query("SELECT * FROM costumes ORDER BY created_at DESC");
$costumes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Costume Shop</title>
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

    <div class="min-h-screen flex flex-col">
        <!-- Admin Nav -->
        <nav class="bg-surface/80 backdrop-blur-md border-b border-white/10 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center gap-8">
                        <span class="text-2xl font-bold text-primary">ZAHA ADMIN</span>
                        <a href="../index.php"
                            class="text-sm font-medium text-secondary hover:text-primary transition-colors">Voir le
                            site</a>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-sm text-gray-300">Bonjour, <span
                                class="text-white font-bold"><?php echo htmlspecialchars($_SESSION['username']); ?></span></span>
                        <a href="../logout.php"
                            class="bg-red-600/20 hover:bg-red-600/30 text-red-400 border border-red-600/30 px-4 py-2 rounded-lg text-sm transition-all">Déconnexion</a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 w-full">

            <div class="flex justify-between items-center mb-8">
                <h1 class="text-4xl font-serif font-bold text-primary">Gestion des Costumes</h1>
                <a href="edit_costume.php"
                    class="bg-primary text-dark font-bold px-6 py-3 rounded-xl hover:bg-secondary transition shadow-lg hover:shadow-xl flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                            clip-rule="evenodd" />
                    </svg>
                    Ajouter un costume
                </a>
            </div>

            <div class="bg-surface/30 rounded-xl border border-surface overflow-hidden backdrop-blur-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-white/10">
                        <thead class="bg-surface/50">
                            <tr>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-secondary uppercase tracking-wider">
                                    Image</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-secondary uppercase tracking-wider">
                                    Nom</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-secondary uppercase tracking-wider">
                                    Catégorie</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-secondary uppercase tracking-wider">
                                    Type</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-secondary uppercase tracking-wider">
                                    Prix</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-secondary uppercase tracking-wider">
                                    État</th>
                                <th
                                    class="px-6 py-4 text-right text-xs font-bold text-secondary uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            <?php foreach ($costumes as $costume): ?>
                                <tr class="hover:bg-white/5 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="h-12 w-12 rounded-lg overflow-hidden border border-white/10">
                                            <img src="<?php echo htmlspecialchars($costume['image']); ?>" alt=""
                                                class="h-full w-full object-cover">
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-cream">
                                            <?php echo htmlspecialchars($costume['name']); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-400">
                                            <?php echo htmlspecialchars($costume['category']); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $costume['type'] === 'sale' ? 'bg-green-900/30 text-green-400 border border-green-900/50' : 'bg-blue-900/30 text-blue-400 border border-blue-900/50'; ?>">
                                            <?php echo $costume['type'] === 'sale' ? 'Vente' : 'Location'; ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-primary font-bold">
                                        <?php echo $costume['type'] === 'sale' ? number_format($costume['price'], 2) . '€' : number_format($costume['rent_price'], 2) . '€/j'; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $costume['available'] ? 'bg-green-900/30 text-green-400 border border-green-900/50' : 'bg-red-900/30 text-red-400 border border-red-900/50'; ?>">
                                            <?php echo $costume['available'] ? 'Disponible' : 'Indisponible'; ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="edit_costume.php?id=<?php echo $costume['id']; ?>"
                                            class="text-secondary hover:text-primary mr-4 transition-colors">Modifier</a>
                                        <a href="#"
                                            class="text-red-400 hover:text-red-300 transition-colors"
                                            onclick="confirmDelete(event, <?php echo $costume['id']; ?>)">Supprimer</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="delete-modal" class="relative z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div id="delete-backdrop" class="fixed inset-0 bg-black/80 backdrop-blur-sm transition-opacity opacity-0"></div>

        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div id="delete-panel"
                    class="relative transform overflow-hidden rounded-2xl bg-surface text-left shadow-2xl transition-all opacity-0 translate-y-4 scale-95 sm:w-full sm:max-w-lg border border-primary/20">
                    <div class="bg-surface px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-900/20 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-xl font-serif font-bold leading-6 text-primary" id="modal-title">Supprimer
                                    le costume</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-300">Êtes-vous sûr de vouloir supprimer ce costume ? Cette
                                        action est irréversible.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-dark/50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-white/5">
                        <button type="button" id="confirm-delete-btn"
                            class="inline-flex w-full justify-center rounded-xl bg-red-600 px-3 py-2 text-sm font-bold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto transition-colors">Supprimer</button>
                        <button type="button" onclick="closeDeleteModal()"
                            class="mt-3 inline-flex w-full justify-center rounded-xl bg-white/10 px-3 py-2 text-sm font-bold text-white shadow-sm hover:bg-white/20 sm:mt-0 sm:w-auto transition-colors">Annuler</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let deleteUrl = '';

        function confirmDelete(event, id) {
            event.preventDefault();
            deleteUrl = 'delete_costume.php?id=' + id;

            const modal = document.getElementById('delete-modal');
            const backdrop = document.getElementById('delete-backdrop');
            const panel = document.getElementById('delete-panel');
            const confirmBtn = document.getElementById('confirm-delete-btn');

            confirmBtn.onclick = function() {
                window.location.href = deleteUrl;
            };

            if (modal && backdrop && panel) {
                modal.classList.remove('hidden');
                requestAnimationFrame(() => {
                    backdrop.classList.remove('opacity-0');
                    panel.classList.remove('opacity-0', 'translate-y-4', 'scale-95');
                });
            }
        }

        function closeDeleteModal() {
            const modal = document.getElementById('delete-modal');
            const backdrop = document.getElementById('delete-backdrop');
            const panel = document.getElementById('delete-panel');

            if (backdrop && panel) {
                backdrop.classList.add('opacity-0');
                panel.classList.add('opacity-0', 'translate-y-4', 'scale-95');
                setTimeout(() => {
                    if (modal) modal.classList.add('hidden');
                }, 300);
            }
        }
    </script>

</body>

</html>