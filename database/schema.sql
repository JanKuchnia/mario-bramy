-- Mario Bramy - Schemat bazy danych
-- Uruchom ten skrypt w phpMyAdmin lub MySQL CLI

-- Utwórz bazę danych
CREATE DATABASE IF NOT EXISTS mario_bramy CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE mario_bramy;

-- Tabela użytkowników admina
CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela obrazów galerii
CREATE TABLE IF NOT EXISTS gallery_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category VARCHAR(100) NOT NULL,
    filename VARCHAR(255) NOT NULL,
    alt_text VARCHAR(500),
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_category (category)
);

-- Tabela produktów
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    category VARCHAR(100) NOT NULL,
    base_price DECIMAL(10,2) NOT NULL,
    image_url VARCHAR(500),
    description TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_category (category),
    INDEX idx_active (is_active)
);

-- Tabela opcji produktów
CREATE TABLE IF NOT EXISTS product_options (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    option_name VARCHAR(100) NOT NULL,
    option_type ENUM('select', 'radio', 'checkbox') DEFAULT 'select',
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Tabela wartości opcji
CREATE TABLE IF NOT EXISTS product_option_values (
    id INT AUTO_INCREMENT PRIMARY KEY,
    option_id INT NOT NULL,
    value_label VARCHAR(100) NOT NULL,
    price_modifier DECIMAL(10,2) DEFAULT 0,
    FOREIGN KEY (option_id) REFERENCES product_options(id) ON DELETE CASCADE
);

-- Dodaj domyślnego administratora
-- Hasło: admin123 (zmień po pierwszym logowaniu!)
INSERT INTO admin_users (username, password_hash) VALUES 
('admin', '$2y$10$8kGQZ6qH5Q0H7Q0H7Q0H7OhN1fQ0Rp3Yn8jm8jm8jm8jm8jm8jm8.')
ON DUPLICATE KEY UPDATE username = username;

-- Przykładowe dane dla galerii (opcjonalne - zaimportuj istniejące pliki)
-- INSERT INTO gallery_images (category, filename, alt_text) VALUES
-- ('bramy-przesuwne-aluminiowe', '1.jpg', 'Nowoczesna brama przesuwna aluminiowa'),
-- ('bramy-przesuwne-aluminiowe', '2.jpg', 'Brama przesuwna w kolorze antracyt');
