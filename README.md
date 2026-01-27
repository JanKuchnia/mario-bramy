![CodeRabbit Pull Request Reviews](https://img.shields.io/coderabbit/prs/github/JanKuchnia/mario-bramy?utm_source=oss&utm_medium=github&utm_campaign=JanKuchnia%2Fmario-bramy&labelColor=171717&color=FF570A&link=https%3A%2F%2Fcoderabbit.ai&label=CodeRabbit+Reviews)
# Mario Bramy - Portal Stron i Bram Aluminiowych

Nowoczesny portal interaktywny dla firmy Mario Bramy z systemem zarządzania, konfiguratorem bramek i integracją opinii z Google.

## ✨ Główne Cechy

- 🔐 **Pełna Autentykacja** - Bcrypt hasła, session 30-min timeout, IP tracking
- 🎨 **8 Stron** - Strona główna, galeria, opinie, kontakt, konfigurator, wkrótce
- 🛒 **Konfigurator Bramek** - Kalkulator cen real-time z opcjami
- 📸 **Galeria** - Lazy loading, WebP conversion, lightbox
- ⭐ **Opinie** - Auto-pull z Google Places API, wyświetlanie top 3 na stronie głównej
- 💾 **Admin Panel** - Zarządzanie galerią, produktami, opcjami, wiadomościami
- 🔒 **Bezpieczeństwo** - CSRF tokens, rate limiting, prepared statements
- ⚡ **Performance** - WebP, lazy loading, gzip compression

## 🚀 Szybki Start (XAMPP)

```bash
# 1. Sklonuj projekt do /opt/lampp/htdocs/mario-bramy
git clone https://github.com/JanKuchnia/mario-bramy.git

# 2. Importuj bazę danych
# Otwórz phpMyAdmin: http://localhost/phpmyadmin
# Utwórz bazę "mario_bramy"
# Importuj: setup_database.sql

# 3. Utwórz pierwszego admina (phpMyAdmin SQL)
INSERT INTO admin_users (username, password_hash, created_at, is_active) 
VALUES ('admin', '$2y$10$N9qo8uLOickgx2ZMRZoMye3c.H6kV2sG7kKSRfPLz8MsCjRAqLd.e', NOW(), 1);
-- Hasło: admin123 (zmień po zalogowaniu!)

# 4. Utwórz foldery uploadów
mkdir -p uploads/portfolio/{bramy-przesuwne,bramy-dwuskrzydlowe,balustrady,garażowe,automatyka,przesla}
mkdir -p uploads/products logs

# 5. Otwórz stronę
http://localhost/mario-bramy/public/index.php

# 6. Zaloguj się do panelu
http://localhost/mario-bramy/admin/login.php
```

## 📂 Struktura Projektu

```
mario-bramy/
├── admin/
│   ├── login.php              # Login page
│   ├── logout.php             # Logout
│   ├── dashboard.php          # Admin panel
│   ├── admin.css              # Admin styling
│   └── api/
│       ├── gallery-*.php      # Gallery CRUD
│       ├── products-*.php     # Products CRUD
│       ├── options-*.php      # Options CRUD
│       ├── choices-*.php      # Choices CRUD
│       └── messages-list.php  # Messages
├── public/
│   ├── index.php              # Homepage
│   ├── nasze-projekty.php     # Gallery
│   ├── opinie.php             # Reviews
│   ├── kontakt.php            # Contact form
│   ├── sklep.php              # Configurator
│   └── wkrotce.php            # Coming soon
├── api/
│   ├── portfolio.php           # Gallery API
│   └── products.php            # Products API
├── config/
│   ├── database.php            # Database connection
│   ├── auth.php                # Authentication
│   ├── security.php            # CSRF, rate limiting
│   ├── constants.php           # Constants
│   └── secrets.php.example     # Template for secrets
├── cron/
│   └── sync-reviews-daily.php  # Google Places API sync
├── includes/
│   ├── header.php              # Shared header
│   └── footer.php              # Shared footer
├── assets/
│   ├── base.css                # Base styles
│   ├── style.css               # Main styles
│   ├── fontawesome.css         # Icons
│   ├── js/
│   │   ├── common.js           # Common functions
│   │   ├── gallery.js          # Gallery functionality
│   │   └── shop.js             # Configurator
│   ├── js/admin.js             # Admin JS
│   └── portfolio/              # Original images
├── setup_database.sql          # Database schema
├── .htaccess                   # URL rewriting & security
├── DEPLOYMENT.md               # Deployment guide
└── README.md                   # This file
```

## 🔑 Endpointy API

### Publiczne
- `GET /api/portfolio.php?category=bramy-przesuwne` - Galeria JSON
- `GET /api/products.php` - Produkty z opcjami JSON

### Admin
- `POST /admin/api/gallery-upload.php` - Upload zdjęcia
- `GET /admin/api/gallery-list.php` - Lista zdjęć
- `DELETE /admin/api/gallery-delete.php` - Usuń zdjęcie
- `POST/PUT /admin/api/products-*.php` - CRUD produktów
- `POST/DELETE /admin/api/options-*.php` - CRUD opcji
- `POST/DELETE /admin/api/choices-*.php` - CRUD wyborów
- `GET /admin/api/messages-list.php` - Lista wiadomości

### Cron
- `GET /cron/sync-reviews-daily.php` - Synchronizuj opinie Google

## 🔐 Bezpieczeństwo

- ✅ **Bcrypt** - Hasła szyfrowane, cost=12
- ✅ **CSRF Tokens** - Na każdy formularz
- ✅ **Rate Limiting** - 5 prób na 15 minut
- ✅ **SQL Injection** - Prepared statements wszędzie
- ✅ **Session Security** - 30-min timeout, IP tracking
- ✅ **Input Validation** - Sanitization & validation
- ✅ **Headers** - X-Frame-Options, X-Content-Type-Options

## 📱 Responsive

- ✅ Desktop (1024px+)
- ✅ Tablet (768px-1023px)
- ✅ Mobile (320px-767px)
- ✅ Hamburger menu na mobile

## 🎨 Tech Stack

- **Backend**: PHP 7.4+
- **Database**: MariaDB / MySQL
- **Frontend**: HTML5, Tailwind CSS, Vanilla JS
- **Images**: WebP conversion, lazy loading
- **Security**: Bcrypt, CSRF tokens, rate limiting
- **API**: RESTful JSON

## 📧 Kontakt

- Email: mario.bramy@mario-bramy.pl
- Tel: +48 123 456 789

---

Dla pełnych instrukcji deploymentu zobacz [DEPLOYMENT.md](DEPLOYMENT.md)