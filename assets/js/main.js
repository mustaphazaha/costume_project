// Configuration
const DATA_URL = 'api/get_costumes.php';

// State
let allCostumes = [];
let filters = {
    type: '',
    category: '',
    size: '',
    color: ''
};
let cart = [];

// Constants
const SIZES = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
const COLORS = ['Noir', 'Bleu', 'Gris', 'Marron', 'Beige', 'Blanc', 'Autre'];
const CATEGORIES = [
    { value: '', label: 'Toutes' },
    { value: 'Classique', label: 'Classique' },
    { value: 'Moderne', label: 'Moderne' },
    { value: 'Vintage', label: 'Vintage' },
    { value: 'Soirée', label: 'Soirée' },
];

// DOM Elements
const heroSection = document.getElementById('hero-section');
const saleGrid = document.getElementById('sale-grid');
const rentGrid = document.getElementById('rent-grid');
const saleSection = document.getElementById('sale-section');
const rentSection = document.getElementById('rent-section');
const saleCount = document.getElementById('sale-count');
const rentCount = document.getElementById('rent-count');
const cartCountBadge = document.getElementById('cart-count');

// Modal Elements
const modal = document.getElementById('costume-modal');
const modalBackdrop = document.getElementById('modal-backdrop');
const modalPanel = document.getElementById('modal-panel');
const modalImage = document.getElementById('modal-image');
const modalCategory = document.getElementById('modal-category');
const modalTitle = document.getElementById('modal-title');
const modalPrice = document.getElementById('modal-price');
const modalDescription = document.getElementById('modal-description');
const modalSizes = document.getElementById('modal-sizes');
let currentCostume = null;

// Initialization
document.addEventListener('DOMContentLoaded', () => {
    // Load Cart
    fetchCart();

    fetchCostumes();
    setupFiltersUI();
    setupScrollEffect();
    updateCartUI();
});

// Fetch Data
async function fetchCostumes() {
    try {
        const response = await fetch(DATA_URL);
        allCostumes = await response.json();
        renderCostumes();
    } catch (error) {
        console.error('Error fetching costumes:', error);
    }
}

// Render Costumes
function renderCostumes() {
    // Filter data
    const filtered = allCostumes.filter(costume => {
        if (filters.type && costume.type !== filters.type) return false;
        if (filters.category && costume.category !== filters.category) return false;
        if (filters.size && !costume.size.some(s => s.trim() === filters.size)) return false;
        if (filters.color && !costume.color.includes(filters.color)) return false;
        return true;
    });

    const saleItems = filtered.filter(c => c.type === 'sale');
    const rentItems = filtered.filter(c => c.type === 'rent');

    // Update Counts
    if (saleCount) saleCount.textContent = `${saleItems.length} costume${saleItems.length > 1 ? 's' : ''}`;
    if (rentCount) rentCount.textContent = `${rentItems.length} costume${rentItems.length > 1 ? 's' : ''}`;

    // Render Grids
    if (saleGrid) saleGrid.innerHTML = saleItems.map(createCostumeCard).join('');
    if (rentGrid) rentGrid.innerHTML = rentItems.map(createCostumeCard).join('');

    // Show/Hide Sections
    if (saleSection && rentSection) {
        if (filters.type === 'sale') {
            saleSection.style.display = 'block';
            rentSection.style.display = 'none';
        } else if (filters.type === 'rent') {
            saleSection.style.display = 'none';
            rentSection.style.display = 'block';
        } else {
            saleSection.style.display = saleItems.length > 0 ? 'block' : 'none';
            rentSection.style.display = rentItems.length > 0 ? 'block' : 'none';
        }
    }
}

// Create Card HTML
function createCostumeCard(costume) {
    const imageUrl = costume.images[0] || 'https://via.placeholder.com/400x500/F0EFEB/344E41?text=Costume';
    const badgeColor = costume.type === 'sale' ? 'bg-sage text-white' : 'bg-sand text-charcoal';
    const badgeText = costume.type === 'sale' ? 'VENTE' : 'LOCATION';
    const priceDisplay = costume.type === 'sale' ? `${costume.price}€` : `${costume.rentPrice}€/jour`;

    return `
        <div onclick="openModal(${costume.id})" class="bg-white/70 group rounded-xl overflow-hidden hover:shadow-xl transition-all duration-300 cursor-pointer border border-sage/10">
            <div class="relative aspect-[3/4] overflow-hidden">
                <img src="${imageUrl}" alt="${costume.name}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                ${!costume.available ? `
                <div class="absolute inset-0 bg-cream/80 backdrop-blur-sm flex items-center justify-center">
                    <span class="text-charcoal font-bold tracking-widest border border-charcoal px-4 py-2">INDISPONIBLE</span>
                </div>` : ''}
                <div class="absolute top-3 right-3">
                    <span class="px-3 py-1 rounded-full text-xs font-bold tracking-wide shadow-sm ${badgeColor}">
                        ${badgeText}
                    </span>
                </div>
            </div>
            <div class="p-5">
                <h3 class="font-serif font-bold text-xl mb-2 line-clamp-1 text-charcoal group-hover:text-sage transition-colors">${costume.name}</h3>
                <p class="text-charcoal/70 text-sm mb-4 line-clamp-2 font-light">${costume.description}</p>
                <div class="flex items-center justify-between pt-4 border-t border-sage/10">
                    <div>
                        <span class="text-xl font-bold text-charcoal">${priceDisplay}</span>
                    </div>
                    <div class="text-xs font-medium text-sage uppercase tracking-wider">
                        ${costume.size.length} taille${costume.size.length > 1 ? 's' : ''}
                    </div>
                </div>
            </div>
        </div>
    `;
}

// Modal Functions
function openModal(id) {
    currentCostume = allCostumes.find(c => c.id == id);
    if (!currentCostume) return;

    modalImage.src = currentCostume.images[0];
    modalCategory.textContent = currentCostume.category;
    modalTitle.textContent = currentCostume.name;
    modalPrice.textContent = currentCostume.type === 'sale' ? `${currentCostume.price}€` : `${currentCostume.rentPrice}€/jour`;
    modalDescription.textContent = currentCostume.description;

    // Render Sizes
    modalSizes.innerHTML = currentCostume.size.map(size => `
        <span class="px-3 py-1 border border-sage/30 rounded text-sm text-charcoal">${size}</span>
    `).join('');

    // Show Modal
    modal.classList.remove('hidden');
    // Small delay for transition
    setTimeout(() => {
        modalBackdrop.classList.remove('opacity-0');
        modalPanel.classList.remove('opacity-0', 'translate-y-4', 'scale-95');
    }, 10);

    document.body.style.overflow = 'hidden'; // Prevent background scrolling
}

function closeModal() {
    modalBackdrop.classList.add('opacity-0');
    modalPanel.classList.add('opacity-0', 'translate-y-4', 'scale-95');

    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }, 300);
}

// Cart Functions
async function fetchCart() {
    try {
        const response = await fetch('api/cart.php?action=get');
        const data = await response.json();
        if (data.success) {
            cart = data.items;
            updateCartUI();
        }
    } catch (error) {
        console.error('Error fetching cart:', error);
    }
}

async function addToCartFromModal() {
    if (!currentCostume) return;

    const formData = new FormData();
    formData.append('action', 'add');
    formData.append('costume_id', currentCostume.id);
    formData.append('size', 'M'); // Default size for now, should be selected from UI

    try {
        const response = await fetch('api/cart.php', {
            method: 'POST',
            body: formData
        });
        const data = await response.json();

        if (data.success) {
            showNotification('Costume ajouté au panier avec succès !', 'success');
            fetchCart(); // Refresh cart
            closeModal();
        } else {
            if (data.message === 'User not logged in') {
                window.location.href = 'login.php';
            } else {
                showNotification('Erreur: ' + data.message, 'error');
            }
        }
    } catch (error) {
        console.error('Error adding to cart:', error);
        showNotification('Une erreur est survenue', 'error');
    }
}

// Notification System
function showNotification(message, type = 'success') {
    const container = document.getElementById('notification-container');
    if (!container) return;

    // Create notification element
    const notification = document.createElement('div');
    notification.className = `
        transform transition-all duration-500 ease-out translate-x-full opacity-0
        flex items-center gap-3 px-6 py-4 rounded-xl shadow-2xl
        backdrop-blur-md border pointer-events-auto min-w-[300px]
        ${type === 'success'
            ? 'bg-dark/90 border-primary/50 text-white'
            : 'bg-red-900/90 border-red-500/50 text-white'}
    `;

    // Icon based on type
    const icon = type === 'success'
        ? `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>`
        : `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>`;

    notification.innerHTML = `
        ${icon}
        <div class="flex-1">
            <h4 class="font-serif font-bold text-sm tracking-wide ${type === 'success' ? 'text-primary' : 'text-red-400'}">
                ${type === 'success' ? 'Succès' : 'Erreur'}
            </h4>
            <p class="text-sm text-gray-200">${message}</p>
        </div>
        <button onclick="this.parentElement.remove()" class="text-gray-400 hover:text-white transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
    `;

    container.appendChild(notification);

    // Animate in
    requestAnimationFrame(() => {
        notification.classList.remove('translate-x-full', 'opacity-0');
    });

    // Auto remove after 3 seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full', 'opacity-0');
        setTimeout(() => {
            notification.remove();
        }, 500); // Wait for transition to finish
    }, 3000);
}

function updateCartUI() {
    if (cartCountBadge) {
        // Calculate total quantity
        const totalQty = cart.reduce((sum, item) => sum + (item.quantity || 1), 0);
        cartCountBadge.textContent = totalQty;
        cartCountBadge.classList.remove('hidden');
        if (totalQty === 0) cartCountBadge.classList.add('hidden');
    }
}

// Setup Filters UI
function setupFiltersUI() {
    // Categories
    const catContainer = document.getElementById('category-filters');
    if (catContainer) {
        catContainer.innerHTML = CATEGORIES.map(cat => `
            <label class="flex items-center cursor-pointer group">
                <input type="radio" name="category" value="${cat.value}" ${filters.category === cat.value ? 'checked' : ''} onchange="updateFilters()" class="mr-3 text-charcoal focus:ring-sage">
                <span class="text-sm text-light group-hover:text-sage transition">${cat.label}</span>
            </label>
        `).join('');
    }

    // Sizes
    const sizeContainer = document.getElementById('size-filters');
    if (sizeContainer) {
        sizeContainer.innerHTML = `
            <label class="flex items-center cursor-pointer group">
                <input type="radio" name="size" value="" ${filters.size === '' ? 'checked' : ''} onchange="updateFilters()" class="mr-3 text-charcoal focus:ring-sage">
                <span class="text-sm text-light group-hover:text-sage transition">Toutes</span>
            </label>
            ${SIZES.map(size => `
                <label class="flex items-center cursor-pointer group">
                    <input type="radio" name="size" value="${size}" ${filters.size === size ? 'checked' : ''} onchange="updateFilters()" class="mr-3 text-charcoal focus:ring-sage">
                    <span class="text-sm text-light group-hover:text-sage transition">${size}</span>
                </label>
            `).join('')}
        `;
    }

    // Colors
    const colorContainer = document.getElementById('color-filters');
    if (colorContainer) {
        colorContainer.innerHTML = `
            <label class="flex items-center cursor-pointer group">
                <input type="radio" name="color" value="" ${filters.color === '' ? 'checked' : ''} onchange="updateFilters()" class="mr-3 text-charcoal focus:ring-sage">
                <span class="text-sm text-light group-hover:text-sage transition">Toutes</span>
            </label>
            ${COLORS.map(color => `
                <label class="flex items-center cursor-pointer group">
                    <input type="radio" name="color" value="${color}" ${filters.color === color ? 'checked' : ''} onchange="updateFilters()" class="mr-3 text-charcoal focus:ring-sage">
                    <span class="text-sm text-light group-hover:text-sage transition">${color}</span>
                </label>
            `).join('')}
        `;
    }
}

// Update Filters
function updateFilters() {
    filters.type = document.querySelector('input[name="type"]:checked')?.value || '';
    filters.category = document.querySelector('input[name="category"]:checked')?.value || '';
    filters.size = document.querySelector('input[name="size"]:checked')?.value || '';
    filters.color = document.querySelector('input[name="color"]:checked')?.value || '';
    renderCostumes();
}

function clearFilters() {
    filters = { type: '', category: '', size: '', color: '' };

    // Reset UI
    document.querySelectorAll('input[type="radio"]').forEach(input => {
        if (input.value === '') input.checked = true;
        else input.checked = false;
    });

    renderCostumes();
}

function filterByType(type) {
    filters.type = type;
    // Update UI radio button
    const radio = document.querySelector(`input[name="type"][value="${type}"]`);
    if (radio) radio.checked = true;
    renderCostumes();

    // Scroll to content
    window.scrollTo({ top: window.innerHeight, behavior: 'smooth' });
}

// Mobile Menu
function toggleMobileMenu() {
    const menu = document.getElementById('mobile-menu');
    if (menu) {
        menu.classList.toggle('hidden');
    }
}

function toggleFilters() {
    const sidebar = document.getElementById('filter-sidebar');
    if (sidebar) {
        sidebar.classList.toggle('hidden');
        sidebar.classList.toggle('fixed');
        sidebar.classList.toggle('inset-0');
        sidebar.classList.toggle('z-50');
        sidebar.classList.toggle('bg-cream');
        sidebar.classList.toggle('p-6');
    }
}

// Scroll Effect & Parallax
function setupScrollEffect() {
    // Parallax Hero
    if (heroSection) {
        window.addEventListener('scroll', () => {
            const scrollY = window.scrollY;
            const windowHeight = window.innerHeight;

            // Move image up at half speed (Parallax)
            if (scrollY <= windowHeight) {
                heroSection.style.transform = `translateY(-${scrollY * 0.5}px)`;
                // Optional: Fade out slightly
                heroSection.style.opacity = Math.max(0, 1 - scrollY / (windowHeight * 1.2));
            }
        });
    }

    // Smooth Content Reveal
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.1
    };

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('opacity-100', 'translate-y-0');
                entry.target.classList.remove('opacity-0', 'translate-y-10');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Observe sections and cards
    const sections = document.querySelectorAll('section');
    sections.forEach(section => {
        section.classList.add('transition-all', 'duration-1000', 'opacity-0', 'translate-y-10');
        observer.observe(section);
    });
}
