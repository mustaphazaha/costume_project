<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<nav class="fixed w-full bg-dark/80 border-surface top-0 z-50 backdrop-blur-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Logo -->
            <a href="index.php" class="flex items-center">
                <span class="text-2xl font-bold text-primary">ZAHA STORE</span>
            </a>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="index.php"
                    class="text-sm font-medium text-primary border-b-2 border-primary transition-colors">ACCUEIL</a>
                <a href="#" onclick="filterByType('sale'); return false;"
                    class="text-sm font-medium text-secondary hover:text-primary transition-colors">VENTE</a>
                <a href="#" onclick="filterByType('rent'); return false;"
                    class="text-sm font-medium text-secondary hover:text-primary transition-colors">LOCATION</a>

                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <a href="admin/dashboard.php"
                        class="text-sm font-medium text-sage hover:text-primary transition-colors">ADMIN</a>
                <?php endif; ?>
            </div>

            <!-- Right Side Icons -->
            <div class="flex items-center space-x-4">
                <!-- Cart -->
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="cart.php" class="relative p-2 text-secondary hover:text-primary transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="9" cy="21" r="1"></circle>
                            <circle cx="20" cy="21" r="1"></circle>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                        </svg>
                        <span id="cart-count"
                            class="absolute top-0 right-0 bg-primary text-dark text-xs rounded-full w-5 h-5 flex items-center justify-center hidden">0</span>
                    </a>
                <?php endif; ?>

                <!-- Account -->
                <div class="flex items-center">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="#" class="p-2 text-secondary hover:text-primary transition-colors flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            <span class="ml-2 text-sm hidden md:inline">Bienvenue,
                                <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                        </a>
                        <a href="#" onclick="confirmLogout(event)"
                            class="ml-2 p-2 text-secondary hover:text-primary transition-colors flex items-center">Déconnexion</a>
                    <?php else: ?>
                            
                        <a href="login.php" class="text-sm font-medium text-secondary hover:text-primary transition-colors flex">
                           <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                class="text-secondary hover:text-primary transition-colors flex items-center mr-1"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg> 
                        Connexion
                        </a>
                        <a href="register.php" class="ml-5 text-sm font-medium text-secondary hover:text-primary transition-colors">S'inscrire</a>
                    <?php endif; ?>
                </div>

                <!-- Mobile Menu Button -->
                <button class="md:hidden p-2 text-secondary hover:text-primary" onclick="toggleMobileMenu()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-dark border-t border-surface">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="index.php"
                    class="block px-3 py-2 rounded-md text-base font-medium text-primary hover:text-white hover:bg-surface transition-colors">ACCUEIL</a>
                <a href="#" onclick="filterByType('sale'); toggleMobileMenu(); return false;"
                    class="block px-3 py-2 rounded-md text-base font-medium text-secondary hover:text-white hover:bg-surface transition-colors">VENTE</a>
                <a href="#" onclick="filterByType('rent'); toggleMobileMenu(); return false;"
                    class="block px-3 py-2 rounded-md text-base font-medium text-secondary hover:text-white hover:bg-surface transition-colors">LOCATION</a>

                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <a href="admin/dashboard.php"
                        class="block px-3 py-2 rounded-md text-base font-medium text-sage hover:text-white hover:bg-surface transition-colors">ADMIN</a>
                <?php endif; ?>
            </div>

            <!-- Mobile User Menu -->
            <div class="pt-4 pb-4 border-t border-surface">
                <div class="flex items-center px-5">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="flex-shrink-0">
                            <div
                                class="h-10 w-10 rounded-full bg-surface flex items-center justify-center text-primary font-bold">
                                <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                            </div>
                        </div>
                        <div class="ml-3">
                            <div class="text-base font-medium leading-none text-white">
                                <?php echo htmlspecialchars($_SESSION['username']); ?>
                            </div>
                            <div class="text-sm font-medium leading-none text-gray-400 mt-1">
                                <?php echo htmlspecialchars($_SESSION['email'] ?? ''); ?>
                            </div>
                        </div>
                        <a href="cart.php"
                            class="ml-auto bg-surface flex-shrink-0 p-1 rounded-full text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white">
                            <span class="sr-only">Voir le panier</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="9" cy="21" r="1"></circle>
                                <circle cx="20" cy="21" r="1"></circle>
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                            </svg>
                        </a>
                    <?php else: ?>
                        <a href="login.php"
                            class="block w-full text-center px-4 py-2 border border-transparent rounded-md shadow-sm text-base font-medium text-dark bg-primary hover:bg-secondary">Se
                            connecter</a>
                    <?php endif; ?>
                </div>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="mt-3 px-2 space-y-1">
                        <a href="#" onclick="confirmLogout(event)"
                            class="block px-3 py-2 rounded-md text-base font-medium text-gray-400 hover:text-white hover:bg-gray-700">Déconnexion</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<div id="logout-modal" class="relative z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div id="logout-backdrop" class="fixed inset-0 bg-black/80 backdrop-blur-sm transition-opacity opacity-0"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center">
            <div id="logout-panel"
                class="relative transform overflow-hidden rounded-2xl bg-surface text-left shadow-2xl transition-all opacity-0 translate-y-4 scale-95 sm:w-full sm:max-w-lg border border-primary/20">
                <div class="bg-surface px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div
                            class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-primary/20 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 class="text-xl font-serif font-bold leading-6 text-primary" id="modal-title">Déconnexion
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-300">Êtes-vous sûr de vouloir vous déconnecter ?</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-dark/50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-white/5">
                    <button type="button" onclick="window.location.href='logout.php'"
                        class="inline-flex w-full justify-center rounded-xl bg-red-600 px-3 py-2 text-sm font-bold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto transition-colors">Oui,
                        me déconnecter</button>
                    <button type="button" onclick="closeLogoutModal()"
                        class="mt-3 inline-flex w-full justify-center rounded-xl bg-white/10 px-3 py-2 text-sm font-bold text-white shadow-sm hover:bg-white/20 sm:mt-0 sm:w-auto transition-colors">Annuler</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmLogout(event) {
        event.preventDefault();
        const modal = document.getElementById('logout-modal');
        const backdrop = document.getElementById('logout-backdrop');
        const panel = document.getElementById('logout-panel');

        if (modal && backdrop && panel) {
            modal.classList.remove('hidden');
            requestAnimationFrame(() => {
                backdrop.classList.remove('opacity-0');
                panel.classList.remove('opacity-0', 'translate-y-4', 'scale-95');
            });
        } else {
            window.location.href = 'logout.php';
        }
    }

    function closeLogoutModal() {
        const modal = document.getElementById('logout-modal');
        const backdrop = document.getElementById('logout-backdrop');
        const panel = document.getElementById('logout-panel');

        if (backdrop && panel) {
            backdrop.classList.add('opacity-0');
            panel.classList.add('opacity-0', 'translate-y-4', 'scale-95');
            setTimeout(() => {
                if (modal) modal.classList.add('hidden');
            }, 300);
        }
    }
</script>