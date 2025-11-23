<?php
session_start();
require_once 'includes/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'admin') {
                header("Location: admin/dashboard.php");
            } else {
                header("Location: index.php");
            }
            exit;
        } else {
            $error = "Identifiants incorrects.";
        }
    } else {
        $error = "Veuillez remplir tous les champs.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Costume Shop</title>
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
                <h1 class="font-serif text-4xl font-bold text-primary mb-2">Connexion</h1>
                <p class="text-secondary">Accédez à votre compte</p>
            </div>

            <?php if ($error): ?>
                <div
                    class="bg-red-900/30 border border-red-900/50 text-red-400 px-4 py-3 rounded-xl mb-6 text-sm backdrop-blur-sm">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-6">
                    <label for="email" class="block text-sm font-bold mb-2 text-secondary">Email</label>
                    <input type="email" id="email" name="email"
                        class="w-full px-4 py-3 rounded-xl bg-dark/50 border border-white/10 text-cream placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                        required>
                </div>

                <div class="mb-8">
                    <label for="password" class="block text-sm font-bold mb-2 text-secondary">Mot de passe</label>
                    <input type="password" id="password" name="password"
                        class="w-full px-4 py-3 rounded-xl bg-dark/50 border border-white/10 text-cream placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                        required>
                </div>

                <button type="submit"
                    class="w-full bg-primary text-dark font-bold py-4 rounded-xl hover:bg-secondary transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                    Se connecter
                </button>
            </form>

            <div class="mt-8 text-center text-sm space-y-3">
                <a href="register.php" class="block text-secondary hover:text-primary transition font-bold">
                    Pas de compte ? S'inscrire
                </a>
                <a href="index.php" class="block text-gray-400 hover:text-primary transition">
                    Retour à l'accueil
                </a>
            </div>
        </div>
    </div>

</body>

</html>