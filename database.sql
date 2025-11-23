-- Create Database
CREATE DATABASE IF NOT EXISTS costume_shop;
USE costume_shop;

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Costumes Table
CREATE TABLE IF NOT EXISTS costumes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    rent_price DECIMAL(10, 2) NOT NULL,
    image VARCHAR(255),
    category VARCHAR(50),
    type ENUM('sale', 'rent') NOT NULL,
    size VARCHAR(255), -- Stored as JSON or comma-separated
    color VARCHAR(255), -- Stored as JSON or comma-separated
    available BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert Admin User (password: admin123)
INSERT INTO users (username, email, password, role) VALUES 
('admin', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Insert Sample Costumes
INSERT INTO costumes (name, description, price, rent_price, image, category, type, size, color, available) VALUES
('Costume 3 Pièces Bleu Nuit', 'Un costume élégant pour les grandes occasions.', 299.99, 45.00, 'https://images.unsplash.com/photo-1594938298603-c8148c472f29?q=80&w=1000&auto=format&fit=crop', 'Classique', 'sale', '["M", "L", "XL"]', '["Bleu"]', 1),
('Smoking Noir Satin', 'Le classique indémodable pour vos soirées de gala.', 350.00, 60.00, 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?q=80&w=1000&auto=format&fit=crop', 'Soirée', 'rent', '["S", "M", "L"]', '["Noir"]', 1),
('Veste Tweed Vintage', 'Un style british authentique pour un look distingué.', 180.00, 30.00, 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?q=80&w=1000&auto=format&fit=crop', 'Vintage', 'sale', '["L", "XL"]', '["Marron", "Beige"]', 1),
('Costume Lin Beige', 'Léger et respirant, parfait pour les mariages d\'été.', 220.00, 40.00, 'https://images.unsplash.com/photo-1487222477894-8943e31ef7b2?q=80&w=1000&auto=format&fit=crop', 'Moderne', 'rent', '["M", "L"]', '["Beige"]', 1),
('Ensemble Bordeaux', 'Osez la couleur avec cet ensemble moderne et audacieux.', 280.00, 50.00, 'https://images.unsplash.com/photo-1552374196-1ab2a1c593e8?q=80&w=1000&auto=format&fit=crop', 'Moderne', 'sale', '["S", "M"]', '["Autre"]', 1),
('Queue de Pie Classique', 'La tenue de cérémonie par excellence.', 400.00, 80.00, 'https://images.unsplash.com/photo-1559582798-678dfc71ccd8?q=80&w=1000&auto=format&fit=crop', 'Soirée', 'rent', '["M", "L", "XL"]', '["Noir", "Blanc"]', 1);

-- Cart Items Table
CREATE TABLE IF NOT EXISTS cart_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    costume_id INT NOT NULL,
    quantity INT DEFAULT 1,
    size VARCHAR(10),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (costume_id) REFERENCES costumes(id) ON DELETE CASCADE
);
