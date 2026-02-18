#!/bin/bash

# SMM Nepal - cPanel Post-Deployment Setup Script
# Run this script after deploying to cPanel to complete setup

set -e  # Exit on error

echo "================================"
echo "SMM Nepal - cPanel Setup Script"
echo "================================"
echo ""

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Get current directory (should be public_html)
CURRENT_DIR=$(pwd)
echo "📁 Working directory: $CURRENT_DIR"
echo ""

# Check if we're in the right directory
if [ ! -f "index.php" ]; then
    echo -e "${RED}❌ Error: index.php not found. Are you in the correct directory?${NC}"
    exit 1
fi

# 1. Create necessary directories
echo -e "${YELLOW}📂 Creating necessary directories...${NC}"
mkdir -p storage/logs
mkdir -p storage/cache
mkdir -p storage/sessions
mkdir -p public/uploads
mkdir -p public/cache
echo -e "${GREEN}✅ Directories created${NC}"
echo ""

# 2. Set directory permissions
echo -e "${YELLOW}🔐 Setting permissions...${NC}"
chmod -R 755 storage
chmod -R 755 public
chmod -R 755 public/uploads 2>/dev/null || true
echo -e "${GREEN}✅ Permissions set${NC}"
echo ""

# 3. Check if .env exists; if not, copy from .env.example
echo -e "${YELLOW}📋 Checking .env configuration...${NC}"
if [ ! -f ".env" ]; then
    if [ -f ".env.example" ]; then
        cp .env.example .env
        echo -e "${YELLOW}⚠️  .env created from .env.example${NC}"
        echo -e "${YELLOW}📝 Please edit .env with your cPanel database credentials:${NC}"
        echo -e "${YELLOW}   nano .env${NC}"
        echo ""
    else
        echo -e "${RED}❌ Neither .env nor .env.example found${NC}"
        exit 1
    fi
else
    echo -e "${GREEN}✅ .env already exists${NC}"
fi
echo ""

# 4. Check PHP version
echo -e "${YELLOW}🐘 Checking PHP version...${NC}"
PHP_VERSION=$(php -v | head -n1)
echo "   $PHP_VERSION"
if ! php -v | grep -q -E "PHP (8\.[0-9]|7\.[4-9])"; then
    echo -e "${YELLOW}⚠️  Warning: PHP 8.0+ recommended${NC}"
fi
echo ""

# 5. Check PHP extensions
echo -e "${YELLOW}🔧 Checking required PHP extensions...${NC}"
EXTENSIONS=("pdo_mysql" "curl" "mbstring" "openssl" "xml" "zip" "json" "fileinfo")
MISSING_EXTENSIONS=()

for ext in "${EXTENSIONS[@]}"; do
    if php -m | grep -qi "$ext"; then
        echo -e "   ${GREEN}✅ $ext${NC}"
    else
        echo -e "   ${RED}❌ $ext (missing)${NC}"
        MISSING_EXTENSIONS+=("$ext")
    fi
done

if [ ${#MISSING_EXTENSIONS[@]} -gt 0 ]; then
    echo -e "${YELLOW}⚠️  Missing extensions: ${MISSING_EXTENSIONS[*]}${NC}"
    echo -e "${YELLOW}📝 Enable them in cPanel → Select PHP Version${NC}"
    echo ""
fi
echo ""

# 6. Check Composer
echo -e "${YELLOW}📦 Checking Composer...${NC}"
if command -v composer &> /dev/null; then
    COMPOSER_VERSION=$(composer --version)
    echo -e "   ${GREEN}✅ $COMPOSER_VERSION${NC}"
else
    echo -e "${RED}❌ Composer not found in PATH${NC}"
    echo -e "${YELLOW}📝 Install Composer or request from hosting provider${NC}"
fi
echo ""

# 7. Run Composer install (if available)
if command -v composer &> /dev/null; then
    echo -e "${YELLOW}📥 Installing Composer dependencies...${NC}"
    composer install --no-dev --optimize-autoloader
    echo -e "${GREEN}✅ Composer install completed${NC}"
    echo ""
fi

# 8. Test database connection (if .env is configured)
echo -e "${YELLOW}🗄️  Testing database connection...${NC}"
if [ -f ".env" ]; then
    # Simple PHP test (requires .env to be properly configured)
    php -r "
    if (file_exists('.env')) {
        \$env = parse_ini_file('.env');
        try {
            \$conn = new PDO(
                'mysql:host=' . \$env['DB_HOST'] . ';dbname=' . \$env['DB_DATABASE'],
                \$env['DB_USERNAME'],
                \$env['DB_PASSWORD'],
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            echo 'Database connection: OK\n';
        } catch (Exception \$e) {
            echo 'Database connection: FAILED - ' . \$e->getMessage() . '\n';
            exit(1);
        }
    }
    " || {
        echo -e "${RED}❌ Database connection failed${NC}"
        echo -e "${YELLOW}📝 Verify DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD in .env${NC}"
    }
else
    echo -e "${YELLOW}⚠️  .env not found; skipping database test${NC}"
fi
echo ""

# 9. Summary
echo "================================"
echo -e "${GREEN}✅ Setup Complete!${NC}"
echo "================================"
echo ""
echo "📋 Next Steps:"
echo "   1. Edit .env with your cPanel database credentials:"
echo "      nano .env"
echo ""
echo "   2. Import database (if not already done):"
echo "      mysql -h localhost -u DB_USER -p DB_NAME < Database.sql"
echo ""
echo "   3. Visit your site in browser:"
echo "      https://yourdomain.com"
echo ""
echo "   4. Log into admin panel:"
echo "      https://yourdomain.com/admin"
echo ""
echo "📚 For detailed instructions, see: CPANEL_DEPLOYMENT.md"
echo ""
