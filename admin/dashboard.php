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
        <nav class="bg-dark/80 border-white/10 sticky top-0 z-50">
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
                                        <a href="delete_costume.php?id=<?php echo $costume['id']; ?>"
                                            class="text-red-400 hover:text-red-300 transition-colors"
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce costume ?');">Supprimer</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

</body>

</html>