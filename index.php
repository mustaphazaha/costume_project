<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Costume Store - Vente et Location de Costumes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#b4a078', // Gold/Bronze from image
                        secondary: '#c8b48c', // Tan from image
                        dark: '#141414', // Dark background from image
                        surface: '#282828', // Dark Gray from image
                        sage: '#b4b48c', // Muted Green/Brown from image
                        cream: '#f0efeb',
                        charcoal: '#141414',
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
    <style>
        /* Custom scrollbar if needed */
        body {
            background-color: #141414;
            color: #f0efeb;
        }
        .glass-panel {
            background: rgba(40, 40, 40, 0.4);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(180, 160, 120, 0.1);
        }
    </style>
</head>
<body class="bg-dark text-cream font-sans antialiased">

    <?php include 'includes/navbar.php'; ?>

    <div id="hero-section" class="fixed inset-0 w-full h-screen z-0 flex items-center justify-center bg-dark transition-opacity duration-75">
        <div class="absolute inset-0 bg-[url('images/hero.png')] bg-cover bg-center"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-dark/40 via-transparent to-dark"></div>
        <div class="relative z-10 text-center px-4">
           <!-- Hero content if any -->
        </div>
    </div>

    <!-- Spacer to push content below the viewport -->
    <div class="h-screen w-full"></div>

    <!-- Main Content Wrapper -->
    <div class="relative z-10 bg-dark min-h-screen shadow-[0_-20px_50px_rgba(0,0,0,0.8)]">
        
        <!-- Mobile Filter Toggle -->
        <div class="md:hidden sticky top-20 z-40 bg-dark/90 backdrop-blur border-b border-surface">
            <button onclick="toggleFilters()" class="w-full px-4 py-3 flex items-center justify-between text-primary">
                <span class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
                    Filtres
                </span>
            </button>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="flex flex-col md:flex-row gap-8">
                
                <!-- Filter Sidebar -->
                <aside id="filter-sidebar" class="hidden md:block w-full md:w-64 flex-shrink-0">
                    <div class="glass-panel rounded-xl p-6 space-y-8 sticky top-24">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-serif font-bold text-primary">Filtres</h3>
                            <button onclick="clearFilters()" class="text-xs text-secondary hover:text-primary transition uppercase tracking-wider">Réinitialiser</button>
                        </div>

                        <!-- Category Filter -->
                        <div>
                            <label class="block text-xs font-bold text-secondary uppercase tracking-wider mb-4">Catégorie</label>
                            <div class="space-y-3" id="category-filters">
                                <!-- Populated by JS -->
                            </div>
                        </div>

                        <!-- Type Filter -->
                        <div>
                            <label class="block text-xs font-bold text-secondary uppercase tracking-wider mb-4">Type</label>
                            <div class="space-y-3">
                                <label class="flex items-center cursor-pointer group">
                                    <input type="radio" name="type" value="" checked onchange="updateFilters()" class="mr-3 text-primary focus:ring-primary bg-transparent border-surface">
                                    <span class="text-sm text-cream group-hover:text-primary transition">Tous</span>
                                </label>
                                <label class="flex items-center cursor-pointer group">
                                    <input type="radio" name="type" value="sale" onchange="updateFilters()" class="mr-3 text-primary focus:ring-primary bg-transparent border-surface">
                                    <span class="text-sm text-cream group-hover:text-primary transition">Vente</span>
                                </label>
                                <label class="flex items-center cursor-pointer group">
                                    <input type="radio" name="type" value="rent" onchange="updateFilters()" class="mr-3 text-primary focus:ring-primary bg-transparent border-surface">
                                    <span class="text-sm text-cream group-hover:text-primary transition">Location</span>
                                </label>
                            </div>
                        </div>

                        <!-- Size Filter -->
                        <div>
                            <label class="block text-xs font-bold text-secondary uppercase tracking-wider mb-4">Taille</label>
                            <div class="space-y-3" id="size-filters">
                                <!-- Populated by JS -->
                            </div>
                        </div>

                        <!-- Color Filter -->
                        <div>
                            <label class="block text-xs font-bold text-secondary uppercase tracking-wider mb-4">Couleur</label>
                            <div class="space-y-3" id="color-filters">
                                <!-- Populated by JS -->
                            </div>
                        </div>
                    </div>
                </aside>

                <!-- Main Grid -->
                <main class="flex-1">
                    <!-- Sale Section -->
                    <section id="sale-section" class="mb-16">
                        <div class="flex items-end justify-between mb-8 border-b border-surface pb-4">
                            <h2 class="text-3xl font-serif font-bold text-primary">En Vente</h2>
                            <span class="text-secondary font-medium" id="sale-count">0 costumes</span>
                        </div>
                        <div id="sale-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Populated by JS -->
                        </div>
                    </section>

                    <!-- Rent Section -->
                    <section id="rent-section">
                        <div class="flex items-end justify-between mb-8 border-b border-surface pb-4">
                            <h2 class="text-3xl font-serif font-bold text-primary">En Location</h2>
                            <span class="text-secondary font-medium" id="rent-count">0 costumes</span>
                        </div>
                        <div id="rent-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Populated by JS -->
                        </div>
                    </section>
                </main>
            </div>
        </div>
    </div>

    <!-- Costume Details Modal -->
    <div id="costume-modal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Backdrop -->
        <div id="modal-backdrop" class="fixed inset-0 bg-black/80 backdrop-blur-sm transition-opacity opacity-0 duration-300 ease-out" onclick="closeModal()"></div>

        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <!-- Modal Panel -->
                <div id="modal-panel" class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all opacity-0 translate-y-4 scale-95 duration-300 ease-out sm:my-8 sm:w-full sm:max-w-4xl">
                    
                    <div class="absolute right-4 top-4 z-10">
                        <button type="button" class="rounded-full bg-white/80 p-2 text-gray-400 hover:text-gray-500 hover:bg-white transition focus:outline-none" onclick="closeModal()">
                            <span class="sr-only">Fermer</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="flex flex-col md:flex-row">
                        <!-- Image Side -->
                        <div class="w-full md:w-1/2 h-96 md:h-auto relative bg-gray-100">
                            <img id="modal-image" src="" alt="" class="absolute inset-0 w-full h-full object-cover">
                        </div>

                        <!-- Content Side -->
                        <div class="w-full md:w-1/2 p-8 md:p-12 bg-cream">
                            <div class="mb-6">
                                <span id="modal-category" class="text-sage font-bold tracking-widest uppercase text-xs"></span>
                                <h2 id="modal-title" class="text-3xl font-serif font-bold text-charcoal mt-2 mb-4"></h2>
                                <p id="modal-price" class="text-2xl font-light text-charcoal"></p>
                            </div>

                            <div class="space-y-6">
                                <div>
                                    <h3 class="text-sm font-bold text-charcoal uppercase tracking-wide mb-2">Description</h3>
                                    <p id="modal-description" class="text-charcoal/70 leading-relaxed"></p>
                                </div>

                                <div>
                                    <h3 class="text-sm font-bold text-charcoal uppercase tracking-wide mb-3">Tailles Disponibles</h3>
                                    <div id="modal-sizes" class="flex flex-wrap gap-2">
                                        <!-- Populated by JS -->
                                    </div>
                                </div>

                                <div class="pt-6 mt-6 border-t border-sage/20">
                                    <button onclick="addToCartFromModal()" class="w-full bg-charcoal text-white font-bold py-4 rounded-xl hover:bg-sage transition-colors duration-300 shadow-lg hover:shadow-xl flex items-center justify-center gap-3 group">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="group-hover:scale-110 transition-transform"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                                        Ajouter au panier
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/main.js"></script>
</body>
</html>
