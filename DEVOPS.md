# 🔧 Quick Reference - Mario Bramy DevOps

## 🚀 XAMPP Setup (1 min)

```bash
# 1. Copy project
cp -r mario-bramy /opt/lampp/htdocs/

# 2. Import database
mysql -u root mario_bramy < setup_database.sql

# 3. Create first admin
mysql -u root mario_bramy -e \
"INSERT INTO admin_users (username, password_hash, created_at, is_active) 
 VALUES ('admin', '\$2y\$10\$N9qo8uLOickgx2ZMRZoMye3c.H6kV2sG7kKSRfPLz8MsCjRAqLd.e', NOW(), 1);"

# 4. Setup folders
mkdir -p /opt/lampp/htdocs/mario-bramy/uploads/portfolio/{bramy-przesuwne,bramy-dwuskrzydlowe,balustrady}
mkdir -p /opt/lampp/htdocs/mario-bramy/logs
chmod 755 /opt/lampp/htdocs/mario-bramy/uploads /opt/lampp/htdocs/mario-bramy/logs

# 5. Start XAMPP
sudo /opt/lampp/bin/xamppcontrol
```

URL: `http://localhost/mario-bramy/public/index.php`

---

## 🌐 Hostinger Deployment (10 min)

### Via SSH

```bash
# 1. Connect
ssh username@host.hostinger.com

# 2. Upload files
sftp> put -r mario-bramy /public_html/

# 3. Create database
# Via Hostinger > Databases > Create

# 4. Import schema
mysql -u user -p password database_name < setup_database.sql

# 5. Update config
nano /public_html/mario-bramy/config/database.php
# Update: $host, $username, $password, $database

# 6. Setup directories
mkdir -p uploads/{portfolio,products} logs
chmod 755 uploads logs

# 7. Create secrets.php
cp config/secrets.php.example config/secrets.php
nano config/secrets.php
# Add: GOOGLE_PLACES_API_KEY, GOOGLE_PLACES_ID

# 8. Setup cron
# Hostinger Panel > Cron Jobs > Add
# Command: /usr/bin/php
# Path: /public_html/mario-bramy/cron/sync-reviews-daily.php
# Time: Daily 02:00
```

---

## 📦 Database Commands

### Import/Export
```bash
# Import
mysql -u root -p mario_bramy < setup_database.sql

# Export (backup)
mysqldump -u root -p mario_bramy > mario_bramy_backup_$(date +%Y%m%d).sql

# Restore from backup
mysql -u root -p mario_bramy < mario_bramy_backup_20240115.sql

# List all databases
mysql -u root -p -e "SHOW DATABASES;"

# Drop database (careful!)
mysql -u root -p -e "DROP DATABASE mario_bramy;"
```

### Quick Queries
```sql
-- Create first admin (if needed)
INSERT INTO admin_users (username, password_hash, created_at, is_active) 
VALUES ('admin', '$2y$10$N9qo8uLOickgx2ZMRZoMye3c.H6kV2sG7kKSRfPLz8MsCjRAqLd.e', NOW(), 1);

-- Count items
SELECT COUNT(*) FROM products;
SELECT COUNT(*) FROM portfolio_images;
SELECT COUNT(*) FROM reviews;
SELECT COUNT(*) FROM contact_submissions;

-- Latest reviews
SELECT * FROM reviews ORDER BY submitted_at DESC LIMIT 5;

-- Pending messages
SELECT * FROM contact_submissions WHERE status = 'pending';

-- All admins
SELECT username, last_login, last_login_ip FROM admin_users;

-- Clear old login attempts
DELETE FROM login_attempts WHERE timestamp < DATE_SUB(NOW(), INTERVAL 1 DAY);
```

---

## 🐛 Troubleshooting

### Database Connection Error
```php
// Check /config/database.php
echo mysqli_error($db);

// Test connection
mysql -u root -p -h localhost -e "SELECT 1;"

// Verify credentials
// XAMPP: username=root, password='', host=localhost
// Hostinger: Check Hostinger panel
```

### Upload Not Working
```bash
# Check permissions
ls -la /uploads/portfolio/
chmod 755 /uploads
chmod 755 /uploads/portfolio

# Check PHP limits in php.ini
post_max_size = 20M
upload_max_filesize = 20M

# Check WebP support
php -r "print_r(gd_info());"
# Look for "WebP Support" => 1
```

### WebP Conversion Fails
```bash
# Check GD Library
php -m | grep gd

# Install GD if missing (Ubuntu)
sudo apt-get install php-gd
sudo systemctl restart apache2

# Check ImageMagick (optional)
php -r "echo extension_loaded('imagick') ? 'Yes' : 'No';"

# Install ImageMagick (Ubuntu)
sudo apt-get install php-imagick
sudo systemctl restart apache2
```

### Reviews Not Syncing
```bash
# Check cron execution
tail /var/log/cron

# Test manually
curl https://mario-bramy.pl/cron/sync-reviews-daily.php

# Check logs
tail -f /public_html/mario-bramy/logs/reviews-sync-*.log

# Verify API credentials in secrets.php
grep GOOGLE_PLACES /public_html/mario-bramy/config/secrets.php
```

### Admin Panel Login Issues
```bash
# Check session directory
ls -la /tmp/ | grep sess

# Clear PHP session cache
rm /tmp/sess_*

# Check session timeout logs
tail -f /public_html/mario-bramy/logs/error.log

# Reset admin password (direct SQL)
UPDATE admin_users SET password_hash = '$2y$10$N9qo8uLOickgx2ZMRZoMye3c.H6kV2sG7kKSRfPLz8MsCjRAqLd.e' WHERE username = 'admin';
-- Password: admin123 (change after login!)
```

---

## 📊 Monitoring & Logs

```bash
# View error log
tail -f /public_html/mario-bramy/logs/error.log

# View review sync log (today)
cat /public_html/mario-bramy/logs/reviews-sync-$(date +%Y-%m-%d).log

# Monitor web server
tail -f /var/log/apache2/access.log
tail -f /var/log/apache2/error.log

# Check disk space
df -h

# Check PHP error logs
grep "php" /var/log/apache2/error.log
```

---

## 🔐 Security Checklist

- [ ] Change default admin password immediately
- [ ] Setup HTTPS (SSL certificate)
- [ ] Configure `/config/database.php` with Hostinger credentials
- [ ] Create `/config/secrets.php` with Google API keys
- [ ] Ensure `/uploads/` and `/logs/` have correct permissions (755)
- [ ] Add `config/database.php` to `.gitignore` before pushing
- [ ] Add `config/secrets.php` to `.gitignore` before pushing
- [ ] Enable error logging in production (disable errors display)
- [ ] Setup regular database backups
- [ ] Monitor error logs regularly
- [ ] Review admin access logs

---

## 🔑 API Key Setup

### Google Places API
1. Go to [Google Cloud Console](https://console.cloud.google.com)
2. Create new project
3. Enable Places API
4. Create API Key (restrict to HTTP referrer)
5. Find your Place ID: https://developers.google.com/places/web-service/overview
6. Add to `/config/secrets.php`:
```php
define('GOOGLE_PLACES_API_KEY', 'AIza...');
define('GOOGLE_PLACES_ID', 'ChIJ...');
```

---

## 📱 Mobile App Config

### CORS Headers (if needed)
```php
// In /config/database.php or response headers
header('Access-Control-Allow-Origin: https://app.mario-bramy.pl');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
```

---

## 🚀 Performance Tips

```bash
# Enable gzip compression
# In .htaccess (already configured)

# Optimize images (batch job)
find /uploads -name "*.jpg" | while read img; do
    convert "$img" -quality 80 "${img%.jpg}.webp"
done

# Clear old cache
find /logs -name "*.log" -mtime +30 -delete

# Database optimization
mysqlcheck -u root -p --optimize mario_bramy

# Check slow queries
mysql -u root -p -e "SET GLOBAL slow_query_log = 'ON';"
```

---

## 📋 Deployment Checklist

```
Pre-Deployment:
☐ All files committed to git (except secrets)
☐ Database backup created
☐ SSL certificate ready
☐ Domain DNS updated
☐ .htaccess reviewed
☐ config/secrets.php prepared
☐ admin password will be changed

During Deployment:
☐ Upload files via SFTP
☐ Import setup_database.sql
☐ Update config/database.php
☐ Create /uploads and /logs directories
☐ Set correct permissions (755)
☐ Copy secrets.php.example → secrets.php
☐ Add Google API credentials

Post-Deployment:
☐ Test homepage: https://mario-bramy.pl
☐ Test admin login: https://mario-bramy.pl/admin/login.php
☐ Change default admin password
☐ Test contact form submission
☐ Test gallery upload (admin panel)
☐ Verify WebP images generated
☐ Check cron job execution
☐ Review error logs
☐ Test mobile responsiveness
☐ Monitor performance
```

---

## 🆘 Emergency Procedures

### Take Site Offline
```bash
# Move index.php to index.php.bak
mv /public_html/mario-bramy/public/index.php /public_html/mario-bramy/public/index.php.bak

# Or create maintenance page
cat > /public_html/mario-bramy/public/index.php << 'EOF'
<?php http_response_code(503); ?>
<!DOCTYPE html>
<html><head><title>Maintenance</title></head>
<body><h1>Site under maintenance. Back soon.</h1></body>
</html>
EOF
```

### Restore from Backup
```bash
# Restore database
mysql -u root -p mario_bramy < mario_bramy_backup_20240115.sql

# Restore files (if using git)
git checkout HEAD~1  # Go back 1 commit
```

### Fix Permissions
```bash
chmod -R 755 /public_html/mario-bramy/
chmod -R 777 /public_html/mario-bramy/uploads
chmod -R 777 /public_html/mario-bramy/logs
```

---

## 📞 Support Contacts

- **Hostinger Support**: https://www.hostinger.com/support
- **Google Cloud**: https://console.cloud.google.com/support
- **PHP Documentation**: https://www.php.net/docs.php
- **MariaDB Docs**: https://mariadb.com/kb/en/

---

**Last Updated**: 2024-01-15
**Project**: Mario Bramy
**Status**: Production Ready

