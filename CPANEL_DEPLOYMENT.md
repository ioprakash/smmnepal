# SMM Nepal - cPanel Deployment Guide

## Pre-Deployment Checklist

Before deploying to cPanel, complete the following steps:

### 1. cPanel Setup (in cPanel Control Panel)
- [ ] Create a new addon domain or use your primary domain
- [ ] Enable SSH access for your cPanel account
- [ ] Verify PHP version is 8.1+ (go to **Select PHP Version** in cPanel)
- [ ] Enable required PHP extensions:
  - [ ] PDO MySQL
  - [ ] cURL
  - [ ] OpenSSL
  - [ ] Mbstring
  - [ ] XML
  - [ ] Zip
  - [ ] JSON
  - [ ] Fileinfo
- [ ] Create MySQL database and database user with all privileges
- [ ] Note down: DB host (usually `localhost`), DB name, DB user, DB password

### 2. Repository Setup
- [ ] Ensure all `.env*` files are git-ignored (check `.gitignore`)
- [ ] Create `.env` file locally with correct DB credentials (see `.env.example`)
- [ ] Test locally: `composer install && php -S 127.0.0.1:8000`

### 3. GitHub/Git Setup (if using cPanel Git Deployment)
- [ ] Push all code to GitHub/GitLab main branch
- [ ] Ensure `.gitignore` excludes: `vendor/`, `node_modules/`, `.env`, `storage/`, `public/uploads/`
- [ ] Create a **personal access token** on GitHub:
  - Go to Settings → Developer Settings → Personal access tokens
  - Generate token with `repo` scope (read/write)
  - Copy token (you'll use this in cPanel)

### 4. SSL Certificate (Optional but Recommended)
- [ ] In cPanel, go to **AutoSSL** and enable auto-renewal
- [ ] Or use **Let's Encrypt** via cPanel for free SSL

### 5. cPanel Git Deployment (Recommended Method)
- [ ] In cPanel → **Git Version Control** (or **Git™ Version Repositories**)
- [ ] Click **Create** → Enter:
  - **Repository URL**: `https://github.com/YOUR_USERNAME/smmnepal.git`
  - **Clone Directory**: Select your public_html folder or subdomain folder
  - **Authentication**: Paste personal access token under "Private Key/Token"
- [ ] Click **Create**
- [ ] Wait for clone to complete

### 6. Post-Deployment (SSH via cPanel Terminal)
After deployment, SSH into your cPanel account and run:

```bash
# Navigate to your deployment directory
cd ~/public_html

# Install Composer dependencies
composer install --no-dev --optimize-autoloader

# Create necessary directories
mkdir -p storage/logs
mkdir -p storage/cache
mkdir -p storage/sessions
mkdir -p public/uploads

# Set permissions (substitute 'nobody' with web server user if different)
chmod -R 755 storage logs public/uploads
chown -R nobody:nobody storage logs public/uploads

# Update .env file (if not already in repo)
nano .env
# Edit: DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD
# Exit: Ctrl+X → Y → Enter

# Database migration (if applicable; adjust command for your app)
php app/console migrate:fresh --seed
# OR just verify connection works:
php -r "require 'app/cn.php'; echo 'Database connected!';"
```

### 7. Verify Deployment
- [ ] Open https://yourdomain.com in browser
- [ ] Check front page loads
- [ ] Log into admin: https://yourdomain.com/admin
- [ ] Test orders page (`/admin/orders`) and clients page (`/admin/clients`)
  - DataTables should make tables searchable/sortable
- [ ] Check error logs (cPanel → **Error log** or SSH: `tail -f /home/username/public_html/storage/logs/error.log`)

---

## Deployment Architecture (cPanel Standard)

```
/home/username/
├── public_html/              ← cPanel document root (web-accessible)
│   ├── index.php             ← app entry point
│   ├── admin/                ← admin routes
│   ├── app/                  ← backend logic
│   ├── myadmin/              ← admin UI
│   ├── public/               ← static assets (CSS/JS/images)
│   ├── vendor/               ← Composer packages
│   ├── storage/              ← logs, cache, sessions (NOT web-accessible ideally)
│   ├── .env                  ← configuration (git-ignored)
│   └── composer.json
├── logs/                      ← optional separate log directory
└── backups/                   ← optional backup folder
```

---

## Configuration via .env (Create/Edit in cPanel)

Create a `.env` file in your public_html root (use cPanel File Manager or SSH):

```env
# Database Configuration
DB_HOST=localhost
DB_DATABASE=your_database_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_secure_password

# Application Settings
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Site Configuration (if app reads from .env)
SITE_NAME="SMM Nepal"
ADMIN_EMAIL=admin@yourdomain.com
```

Then, in your app's connection file (e.g., `app/cn.php`), load .env:
```php
if (file_exists(__DIR__ . '/../.env')) {
    $env = parse_ini_file(__DIR__ . '/../.env');
    $db_host = $env['DB_HOST'] ?? 'localhost';
    $db_database = $env['DB_DATABASE'] ?? 'smmnepal';
    // ... etc
}
```

---

## Troubleshooting

### Blank White Page / 500 Error
- [ ] Check cPanel error log: **Error log** in cPanel or SSH: `tail /home/username/public_html/error_log`
- [ ] Ensure `public/` folder is writable by web server (chmod 755)
- [ ] Verify PHP version and extensions are enabled

### Database Connection Failed
- [ ] SSH and run: `mysql -h localhost -u DB_USER -p DB_NAME` (test connection)
- [ ] Verify DB_HOST, DB_USERNAME, DB_PASSWORD in `.env` or config file
- [ ] Check MySQL user privileges: `GRANT ALL ON smmnepal.* TO 'db_user'@'localhost';`

### Composer Install Fails
- [ ] In cPanel, go to **Select PHP Version** and enable PHP CLI (for SSH access)
- [ ] SSH and run: `php -v` (verify PHP 8.1+)
- [ ] Run: `composer --version` (ensure Composer is available)
- [ ] If missing, cPanel often provides Composer via SSH; if not, install via PECL or cPanel App Installer

### Permission Denied Errors
- [ ] SSH: `chmod -R 755 storage public/uploads` (web server can read/write)
- [ ] SSH: `chown -R nobody:nobody storage public/uploads` (adjust `nobody` if needed; see cPanel web server user)

### Static Assets (CSS/JS) Not Loading
- [ ] Check nginx/Apache rewrite rules: `.htaccess` for Apache should have proper rules
- [ ] Ensure `public/` folder is accessible: visit https://yourdomain.com/public/admin/style.css (should load or 404, not 403)

---

## Auto-Deployment with GitHub Webhooks (Advanced)

For automatic deployment on GitHub push:

1. In cPanel **Git Version Control**, enable **Auto Deploy** if available, or
2. Set up a webhook in GitHub → Settings → Webhooks:
   - Payload URL: Ask cPanel support for webhook endpoint (usually `https://cpanel.yourdomain.com/cgi-sys/deploy_git.cgi`)
   - Content type: `application/json`
   - Events: Push events
   - Save

---

## Quick Reference Commands (SSH)

```bash
# SSH into cPanel account
ssh username@yourdomain.com

# Check PHP version
php -v

# Check enabled extensions
php -m

# Test database connection
mysql -h localhost -u db_user -p db_name

# Run Composer
composer install --no-dev --optimize-autoloader

# Check storage permissions
ls -la storage/

# View error log (tail last 50 lines)
tail -50 /home/username/public_html/error_log

# Restart PHP (if needed)
# Note: cPanel restarts PHP automatically; may not be available to users

# Check disk usage
du -sh ~/public_html
```

---

## Support

For cPanel-specific issues:
- Contact your hosting provider's support
- Check cPanel documentation: https://documentation.cpanel.net/

For application issues:
- Check app error logs in `storage/logs/`
- Review app documentation in `README.md` and `DEPLOYMENT.md`
