CREATE DATABASE IF NOT EXISTS mario_bramy CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS 'mario_admin'@'localhost' IDENTIFIED BY 'MarioBramy2024!Secure';
CREATE USER IF NOT EXISTS 'mario_admin'@'127.0.0.1' IDENTIFIED BY 'MarioBramy2024!Secure';
GRANT ALL PRIVILEGES ON mario_bramy.* TO 'mario_admin'@'localhost';
GRANT ALL PRIVILEGES ON mario_bramy.* TO 'mario_admin'@'127.0.0.1';
FLUSH PRIVILEGES;
