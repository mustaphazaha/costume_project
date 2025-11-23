<?php
session_start();
require_once 'includes/db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "Veuillez remplir tous les champs.";
    } elseif ($password !== $confirm_password) {
        $error = "Les mots de passe ne correspondent pas.";
    } else {
        // Check if email or username exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
        $stmt->execute([$email, $username]);
        if ($stmt->fetch()) {
            $error = "Cet email ou nom d'utilisateur est déjà utilisé.";
        } else {
            // Insert new user
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'user')");
            if ($stmt->execute([$username, $email, $hash])) {
                $success = "Compte créé avec succès ! Vous pouvez maintenant vous connecter.";
            } else {
                $error = "Une erreur est survenue lors de l'inscription.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Costume Shop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        sage: '#8da399',
                        cream: '#f0efeb',
                        charcoal: '#2f3e46',
                        sand: '#d4c5b0',
                        'off-white': '#f9f9f9'
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
<body class="bg-cream font-sans text-charcoal min-h-screen flex items-center justify-center">

    <div class="bg-white p-8 rounded-xl shadow-xl w-full max-w-md border border-sage/20">
        <div class="text-center mb-8">
            <h1 class="font-serif text-3xl font-bold text-charcoal mb-2">Inscription</h1>
            <p class="text-charcoal/60">Créez votre compte</p>
        </div>

        <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 text-sm">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 text-sm">
                <?php echo htmlspecialchars($success); ?>
                <div class="mt-2">
                    <a href="login.php" class="font-bold underline">Se connecter</a>
                </div>
            </div>
        <?php else: ?>

        <form method="POST" action="">
            <div class="mb-4">
                <label for="username" class="block text-sm font-bold mb-2 text-charcoal/80">Nom d'utilisateur</label>
                <input type="text" id="username" name="username" class="w-full px-4 py-3 rounded-lg bg-off-white border border-gray-200 focus:border-sage focus:ring-1 focus:ring-sage outline-none transition" required>
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-bold mb-2 text-charcoal/80">Email</label>
                <input type="email" id="email" name="email" class="w-full px-4 py-3 rounded-lg bg-off-white border border-gray-200 focus:border-sage focus:ring-1 focus:ring-sage outline-none transition" required>
            </div>
            
            <div class="mb-4">
                <label for="password" class="block text-sm font-bold mb-2 text-charcoal/80">Mot de passe</label>
                <input type="password" id="password" name="password" class="w-full px-4 py-3 rounded-lg bg-off-white border border-gray-200 focus:border-sage focus:ring-1 focus:ring-sage outline-none transition" required>
            </div>

            <div class="mb-8">
                <label for="confirm_password" class="block text-sm font-bold mb-2 text-charcoal/80">Confirmer le mot de passe</label>
                <input type="password" id="confirm_password" name="confirm_password" class="w-full px-4 py-3 rounded-lg bg-off-white border border-gray-200 focus:border-sage focus:ring-1 focus:ring-sage outline-none transition" required>
            </div>

            <button type="submit" class="w-full bg-charcoal text-white font-bold py-3 rounded-lg hover:bg-sage transition-colors duration-300 shadow-lg hover:shadow-xl">
                S'inscrire
            </button>
        </form>
        <?php endif; ?>

        <div class="mt-6 text-center text-sm text-charcoal/60">
            <a href="login.php" class="hover:text-sage transition">Déjà un compte ? Se connecter</a>
        </div>
    </div>

</body>
</html>
