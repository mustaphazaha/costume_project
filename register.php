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
    <style>
        body {
            background-image: url('images/hero.png');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
        }
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(20, 20, 20, 0.8);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            z-index: 0;
        }
    </style>
</head>
<body class="bg-dark font-sans text-cream min-h-screen relative">

    <!-- Content -->
    <div class="relative z-10 min-h-screen flex items-center justify-center px-4 py-12">
        <div class="bg-surface/40 backdrop-blur-md p-10 rounded-2xl shadow-2xl w-full max-w-md border border-white/10">
            <div class="text-center mb-8">
                <h1 class="font-serif text-4xl font-bold text-primary mb-2">Inscription</h1>
                <p class="text-secondary">Créez votre compte</p>
            </div>

            <?php if ($error): ?>
                <div class="bg-red-900/30 border border-red-900/50 text-red-400 px-4 py-3 rounded-xl mb-6 text-sm backdrop-blur-sm">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="bg-green-900/30 border border-green-900/50 text-green-400 px-4 py-3 rounded-xl mb-6 text-sm backdrop-blur-sm">
                    <?php echo htmlspecialchars($success); ?>
                    <div class="mt-3">
                        <a href="login.php" class="inline-block bg-primary text-dark font-bold px-4 py-2 rounded-lg hover:bg-secondary transition-all">
                            Se connecter
                        </a>
                    </div>
                </div>
            <?php else: ?>

            <form method="POST" action="">
                <div class="mb-5">
                    <label for="username" class="block text-sm font-bold mb-2 text-secondary">Nom d'utilisateur</label>
                    <input type="text" id="username" name="username" 
                        class="w-full px-4 py-3 rounded-xl bg-dark/50 border border-white/10 text-cream placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all" 
                        required>
                </div>

                <div class="mb-5">
                    <label for="email" class="block text-sm font-bold mb-2 text-secondary">Email</label>
                    <input type="email" id="email" name="email" 
                        class="w-full px-4 py-3 rounded-xl bg-dark/50 border border-white/10 text-cream placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all" 
                        required>
                </div>
                
                <div class="mb-5">
                    <label for="password" class="block text-sm font-bold mb-2 text-secondary">Mot de passe</label>
                    <input type="password" id="password" name="password" 
                        class="w-full px-4 py-3 rounded-xl bg-dark/50 border border-white/10 text-cream placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all" 
                        required>
                </div>

                <div class="mb-8">
                    <label for="confirm_password" class="block text-sm font-bold mb-2 text-secondary">Confirmer le mot de passe</label>
                    <input type="password" id="confirm_password" name="confirm_password" 
                        class="w-full px-4 py-3 rounded-xl bg-dark/50 border border-white/10 text-cream placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all" 
                        required>
                </div>

                <button type="submit" 
                    class="w-full bg-primary text-dark font-bold py-4 rounded-xl hover:bg-secondary transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                    S'inscrire
                </button>
            </form>
            <?php endif; ?>

            <div class="mt-8 text-center text-sm space-y-3">
                <a href="login.php" class="block text-secondary hover:text-primary transition font-bold">
                    Déjà un compte ? Se connecter
                </a>
                <a href="index.php" class="block text-gray-400 hover:text-primary transition">
                    Retour à l'accueil
                </a>
            </div>
        </div>
    </div>

</body>
</html>