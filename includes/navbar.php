<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<nav class="bg-dark border-surface sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Logo -->
            <a href="index.php" class="flex items-center">
                <span class="text-2xl font-bold text-primary">ZAHA STORE</span>
            </a>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="index.php" class="text-sm font-medium text-primary border-b-2 border-primary transition-colors">ACCUEIL</a>
                <a href="#" onclick="filterByType('sale'); return false;" class="text-sm font-medium text-secondary hover:text-primary transition-colors">VENTE</a>
                <a href="#" onclick="filterByType('rent'); return false;" class="text-sm font-medium text-secondary hover:text-primary transition-colors">LOCATION</a>
                
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <a href="admin/dashboard.php" class="text-sm font-medium text-sage hover:text-primary transition-colors">ADMIN</a>
                <?php endif; ?>
            </div>

            <!-- Right Side Icons -->
            <div class="flex items-center space-x-4">
                <!-- Search -->
                <div class="hidden md:flex items-center bg-surface rounded-full px-4 py-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-secondary mr-2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <input type="text" placeholder="Rechercher" class="bg-transparent border-none outline-none text-cream text-sm w-32 placeholder-secondary/50">
                </div>

                <!-- Cart -->
                <a href="#" class="relative p-2 text-secondary hover:text-primary transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                    <span id="cart-count" class="absolute top-0 right-0 bg-primary text-dark text-xs rounded-full w-5 h-5 flex items-center justify-center hidden">0</span>
                </a>

                <!-- Account -->
                <div class="relative group">
                    <a href="<?php echo isset($_SESSION['user_id']) ? '#' : 'login.php'; ?>" class="p-2 text-secondary hover:text-primary transition-colors flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        <?php if (isset($_SESSION['username'])): ?>
                            <span class="ml-2 text-sm hidden md:inline"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                        <?php endif; ?>
                    </a>
                    
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="absolute right-0 mt-2 w-48 bg-surface rounded-md shadow-lg py-1 hidden group-hover:block z-50">
                            <a href="logout.php" class="block px-4 py-2 text-sm text-cream hover:bg-dark">Déconnexion</a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Mobile Menu Button -->
                <button class="md:hidden p-2 text-secondary hover:text-primary" onclick="toggleMobileMenu()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden py-4 border-t border-surface">
            <a href="index.php" class="block py-2 text-secondary hover:text-primary">ACCUEIL</a>
            <a href="#" onclick="filterByType('sale'); return false;" class="block py-2 text-secondary hover:text-primary">VENTE</a>
            <a href="#" onclick="filterByType('rent'); return false;" class="block py-2 text-secondary hover:text-primary">LOCATION</a>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <a href="admin/dashboard.php" class="block py-2 text-sage hover:text-primary">ADMIN</a>
            <?php endif; ?>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="logout.php" class="block py-2 text-secondary hover:text-primary">DÉCONNEXION</a>
            <?php else: ?>
                <a href="login.php" class="block py-2 text-secondary hover:text-primary">CONNEXION</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
