<?php
session_start();
require_once '../includes/db.php';

// Check if admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$costume = [
    'id' => '',
    'name' => '',
    'description' => '',
    'price' => '',
    'rent_price' => '',
    'image' => '',
    'category' => '',
    'type' => 'sale',
    'size' => [],
    'color' => [],
    'available' => 1
];

$isEditing = false;
$error = '';

// Handle Edit Mode
if (isset($_GET['id'])) {
    $isEditing = true;
    $stmt = $pdo->prepare("SELECT * FROM costumes WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $fetched = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($fetched) {
        $costume = $fetched;
        // Decode JSON fields if they are strings
        $costume['size'] = is_string($costume['size']) ? json_decode($costume['size'], true) : $costume['size'];
        $costume['color'] = is_string($costume['color']) ? json_decode($costume['color'], true) : $costume['color'];

        // Handle potential nulls from json_decode
        if (!is_array($costume['size']))
            $costume['size'] = [];
        if (!is_array($costume['color']))
            $costume['color'] = [];
    }
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $rent_price = $_POST['rent_price'];
    $image = $_POST['image'];
    $category = $_POST['category'];
    $type = $_POST['type'];
    $available = isset($_POST['available']) ? 1 : 0;

    // Process arrays (sizes/colors)
    $sizes = isset($_POST['sizes']) ? $_POST['sizes'] : []; // Array of selected sizes
    $colors = isset($_POST['colors']) ? explode(',', $_POST['colors']) : []; // Comma separated string
    $colors = array_map('trim', $colors);

    $sizesJson = json_encode($sizes);
    $colorsJson = json_encode($colors);

    // Debug logging
    error_log("Form submitted: " . print_r($_POST, true));

    if ($isEditing) {
        $stmt = $pdo->prepare("UPDATE costumes SET name=?, description=?, price=?, rent_price=?, image=?, category=?, type=?, size=?, color=?, available=? WHERE id=?");
        $stmt->execute([$name, $description, $price, $rent_price, $image, $category, $type, $sizesJson, $colorsJson, $available, $_GET['id']]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO costumes (name, description, price, rent_price, image, category, type, size, color, available) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $description, $price, $rent_price, $image, $category, $type, $sizesJson, $colorsJson, $available]);
    }

    header("Location: dashboard.php");
    exit;
}

// Helper for sizes
$allSizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $isEditing ? 'Modifier' : 'Ajouter'; ?> un Costume - Admin</title>
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

<body class="bg-dark text-cream font-sans antialiased">

    <div class="min-h-screen flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div
            class="max-w-3xl w-full space-y-8 bg-surface/30 p-10 rounded-2xl shadow-2xl border border-surface backdrop-blur-md">
            <div>
                <h2 class="mt-6 text-center text-3xl font-serif font-bold text-primary">
                    <?php echo $isEditing ? 'Modifier le Costume' : 'Ajouter un Nouveau Costume'; ?>
                </h2>
            </div>

            <form class="mt-8 space-y-6" action="" method="POST">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-secondary mb-2">Nom du Costume</label>
                        <input type="text" name="name" required
                            value="<?php echo htmlspecialchars($costume['name']); ?>"
                            class="block w-full bg-dark/50 border border-white/10 rounded-xl shadow-sm py-3 px-4 text-cream placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    </div>

                    <!-- Category -->
                    <div>
                        <label class="block text-sm font-medium text-secondary mb-2">Catégorie</label>
                        <select name="category"
                            class="block w-full bg-dark/50 border border-white/10 rounded-xl shadow-sm py-3 px-4 text-cream focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                            <option value="Classique" <?php echo $costume['category'] == 'Classique' ? 'selected' : ''; ?>>Classique</option>
                            <option value="Moderne" <?php echo $costume['category'] == 'Moderne' ? 'selected' : ''; ?>>
                                Moderne</option>
                            <option value="Vintage" <?php echo $costume['category'] == 'Vintage' ? 'selected' : ''; ?>>
                                Vintage</option>
                            <option value="Soirée" <?php echo $costume['category'] == 'Soirée' ? 'selected' : ''; ?>>
                                Soirée</option>
                        </select>
                    </div>

                    <!-- Type -->
                    <div>
                        <label class="block text-sm font-medium text-secondary mb-2">Type</label>
                        <select name="type"
                            class="block w-full bg-dark/50 border border-white/10 rounded-xl shadow-sm py-3 px-4 text-cream focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                            <option value="sale" <?php echo $costume['type'] == 'sale' ? 'selected' : ''; ?>>Vente
                            </option>
                            <option value="rent" <?php echo $costume['type'] == 'rent' ? 'selected' : ''; ?>>Location
                            </option>
                        </select>
                    </div>

                    <!-- Prices -->
                    <div>
                        <label class="block text-sm font-medium text-secondary mb-2">Prix Vente (€)</label>
                        <input type="number" step="0.01" name="price"
                            value="<?php echo htmlspecialchars($costume['price']); ?>"
                            class="block w-full bg-dark/50 border border-white/10 rounded-xl shadow-sm py-3 px-4 text-cream placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-secondary mb-2">Prix Location (€/jour)</label>
                        <input type="number" step="0.01" name="rent_price"
                            value="<?php echo htmlspecialchars($costume['rent_price']); ?>"
                            class="block w-full bg-dark/50 border border-white/10 rounded-xl shadow-sm py-3 px-4 text-cream placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    </div>

                    <!-- Image URL -->
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-secondary mb-2">URL de l'image</label>
                        <input type="url" name="image" required
                            value="<?php echo htmlspecialchars($costume['image']); ?>"
                            class="block w-full bg-dark/50 border border-white/10 rounded-xl shadow-sm py-3 px-4 text-cream placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    </div>

                    <!-- Description -->
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-secondary mb-2">Description</label>
                        <textarea name="description" rows="3"
                            class="block w-full bg-dark/50 border border-white/10 rounded-xl shadow-sm py-3 px-4 text-cream placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all"><?php echo htmlspecialchars($costume['description']); ?></textarea>
                    </div>

                    <!-- Sizes -->
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-secondary mb-2">Tailles Disponibles</label>
                        <div class="flex flex-wrap gap-4 bg-dark/50 p-4 rounded-xl border border-white/10">
                            <?php foreach ($allSizes as $size): ?>
                                <label class="inline-flex items-center cursor-pointer group">
                                    <input type="checkbox" name="sizes[]" value="<?php echo $size; ?>" <?php echo in_array($size, $costume['size']) ? 'checked' : ''; ?>
                                        class="form-checkbox h-5 w-5 text-primary rounded border-white/20 bg-surface focus:ring-primary focus:ring-offset-0 transition-all">
                                    <span
                                        class="ml-2 text-gray-300 group-hover:text-primary transition-colors"><?php echo $size; ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Colors -->
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-secondary mb-2">Couleurs (séparées par des
                            virgules)</label>
                        <input type="text" name="colors"
                            value="<?php echo htmlspecialchars(implode(', ', $costume['color'])); ?>"
                            placeholder="Noir, Bleu, Rouge..."
                            class="block w-full bg-dark/50 border border-white/10 rounded-xl shadow-sm py-3 px-4 text-cream placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    </div>

                    <!-- Available -->
                    <div class="col-span-2">
                        <label class="inline-flex items-center cursor-pointer group">
                            <input type="checkbox" name="available" value="1" <?php echo $costume['available'] ? 'checked' : ''; ?>
                                class="form-checkbox h-5 w-5 text-primary rounded border-white/20 bg-surface focus:ring-primary focus:ring-offset-0 transition-all">
                            <span
                                class="ml-2 text-gray-300 group-hover:text-primary transition-colors font-medium">Disponible
                                en stock</span>
                        </label>
                    </div>
                </div>

                <div class="flex justify-end space-x-4 pt-6 border-t border-white/10">
                    <a href="dashboard.php"
                        class="bg-transparent border border-white/10 py-3 px-6 rounded-xl text-sm font-bold text-gray-300 hover:bg-white/5 hover:text-white transition-all">
                        Annuler
                    </a>
                    <button type="submit" id="submit-btn"
                        class="inline-flex justify-center py-3 px-6 border border-transparent shadow-lg text-sm font-bold rounded-xl text-dark bg-primary hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all transform hover:scale-105">
                        <?php echo $isEditing ? 'Mettre à jour' : 'Créer le costume'; ?>
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>

</html>