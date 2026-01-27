-- Mario Bramy - MySQL/MariaDB Schema
-- Execute this file to setup the database

CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    last_login_ip VARCHAR(45) NULL,
    is_active BOOLEAN DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    icon_url VARCHAR(255),
    display_on_homepage BOOLEAN DEFAULT 1,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS portfolio_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(255) UNIQUE NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS portfolio_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    filename VARCHAR(255) NOT NULL,
    alt_text VARCHAR(255),
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_visible BOOLEAN DEFAULT 1,
    sort_order INT DEFAULT 0,
    FOREIGN KEY (category_id) REFERENCES portfolio_categories(id) ON DELETE CASCADE,
    INDEX idx_category (category_id),
    INDEX idx_visible (is_visible)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    category_id INT,
    base_price DECIMAL(10, 2) NOT NULL,
    description TEXT,
    image_url VARCHAR(255),
    is_visible BOOLEAN DEFAULT 1,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
    INDEX idx_visible (is_visible)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS product_options (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    option_key VARCHAR(100) NOT NULL,
    option_label VARCHAR(255) NOT NULL,
    option_type ENUM('select', 'checkbox', 'radio') DEFAULT 'select',
    sort_order INT DEFAULT 0,
    is_required BOOLEAN DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX idx_product (product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS product_option_choices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    option_id INT NOT NULL,
    label VARCHAR(255) NOT NULL,
    value VARCHAR(255) NOT NULL,
    price_modifier DECIMAL(10, 2) DEFAULT 0,
    sort_order INT DEFAULT 0,
    is_default BOOLEAN DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (option_id) REFERENCES product_options(id) ON DELETE CASCADE,
    INDEX idx_option (option_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    author_name VARCHAR(255) NOT NULL,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    text TEXT NOT NULL,
    source ENUM('google', 'manual') DEFAULT 'manual',
    google_review_id VARCHAR(255) UNIQUE NULL,
    is_featured BOOLEAN DEFAULT 0,
    is_visible BOOLEAN DEFAULT 1,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_visible (is_visible),
    INDEX idx_featured (is_featured),
    INDEX idx_source (source)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS company_info (
    id INT PRIMARY KEY DEFAULT 1,
    name VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(255) NOT NULL,
    address VARCHAR(255) NOT NULL,
    google_maps_embed_url TEXT,
    facebook_url VARCHAR(255),
    business_hours_mon_fri VARCHAR(50),
    business_hours_sat VARCHAR(50),
    business_hours_sun VARCHAR(50),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS contact_submissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    product_interest VARCHAR(255),
    estimated_price DECIMAL(10, 2),
    message TEXT NOT NULL,
    ip_address VARCHAR(45),
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'viewed', 'replied', 'archived') DEFAULT 'pending',
    reply_message TEXT,
    replied_at TIMESTAMP NULL,
    INDEX idx_status (status),
    INDEX idx_submitted (submitted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS quotation_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    customer_name VARCHAR(255) NOT NULL,
    customer_email VARCHAR(255) NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    selected_options JSON,
    calculated_price DECIMAL(10, 2) NOT NULL,
    notes TEXT,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'viewed', 'replied', 'archived') DEFAULT 'pending',
    admin_notes TEXT,
    replied_at TIMESTAMP NULL,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX idx_status (status),
    INDEX idx_submitted (submitted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS login_attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip_address VARCHAR(45) NOT NULL,
    username VARCHAR(255),
    attempt_count INT DEFAULT 1,
    last_attempt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_blocked BOOLEAN DEFAULT 0,
    blocked_until TIMESTAMP NULL,
    UNIQUE KEY unique_ip_username (ip_address, username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default data
INSERT INTO company_info (id, name, phone, email, address, business_hours_mon_fri, business_hours_sat, business_hours_sun) VALUES
(1, 'Mario Bramy', '+48 668 197 170', 'mario.bramy@gmail.com', 'Wiśniowa 782, 32-412 Wiśniowa', '08:00-17:00', '09:00-13:00', 'Zamknięte');

-- Portfolio categories
INSERT INTO portfolio_categories (slug, name, description, sort_order) VALUES
('wszystkie', 'Wszystkie', 'Wszystkie realizacje', 0),
('bramy-przesuwne', 'Bramy Przesuwne Aluminiowe', 'Nowoczesne bramy przesuwne', 1),
('bramy-dwuskrzydlowe', 'Bramy Dwuskrzydłowe', 'Eleganckie bramy dwuskrzydłowe', 2),
('balustrady', 'Balustrady Aluminiowe', 'Bezpieczne balustrady', 3),
('bramy-garazowe', 'Bramy Garażowe', 'Bramy dla garaży', 4),
('automatyka', 'Automatyka', 'Systemy automatyczne', 5),
('przesla-ogrodzeniowe', 'Przęsła Ogrodzeniowe', 'Ogrodzenia aluminiowe', 6);

-- Service categories
INSERT INTO categories (name, description, icon_url, display_on_homepage, sort_order) VALUES
('Bramy Przesuwne', 'Nowoczesne bramy aluminiowe przesuwne', '/assets/icons/gate-sliding.png', 1, 0),
('Bramy Dwuskrzydłowe', 'Eleganckie bramy dwuskrzydłowe', '/assets/icons/gate-double.png', 1, 1),
('Automatyka', 'Bezpieczne systemy automatyczne', '/assets/icons/automation.png', 1, 2),
('Balustrady', 'Trwałe balustrady aluminiowe', '/assets/icons/railing.png', 1, 3);
