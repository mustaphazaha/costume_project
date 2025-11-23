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
            <h1 class="font-serif text-3xl font-bold text-charcoal mb-2">Connexion</h1>
            <p class="text-charcoal/60">Accédez à votre compte</p>
        </div>

        <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 text-sm">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-6">
                <label for="email" class="block text-sm font-bold mb-2 text-charcoal/80">Email</label>
                <input type="email" id="email" name="email" class="w-full px-4 py-3 rounded-lg bg-off-white border border-gray-200 focus:border-sage focus:ring-1 focus:ring-sage outline-none transition" required>
            </div>
            
            <div class="mb-8">
                <label for="password" class="block text-sm font-bold mb-2 text-charcoal/80">Mot de passe</label>
                <input type="password" id="password" name="password" class="w-full px-4 py-3 rounded-lg bg-off-white border border-gray-200 focus:border-sage focus:ring-1 focus:ring-sage outline-none transition" required>
            </div>

            <button type="submit" class="w-full bg-charcoal text-white font-bold py-3 rounded-lg hover:bg-sage transition-colors duration-300 shadow-lg hover:shadow-xl">
                Se connecter
            </button>
        </form>

        <div class="mt-6 text-center text-sm text-charcoal/60 space-y-2">
            <a href="register.php" class="block hover:text-sage transition font-bold">Pas de compte ? S'inscrire</a>
            <a href="index.php" class="block hover:text-sage transition">Retour à l'accueil</a>
        </div>
    </div>

</body>
</html>
