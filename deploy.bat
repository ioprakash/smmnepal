@echo off
REM SMM Nepal - cPanel Post-Deployment Setup Script (Windows)
REM Run this script in Command Prompt after deploying to cPanel

setlocal enabledelayedexpansion

echo.
echo ================================
echo SMM Nepal - cPanel Setup Script
echo ================================
echo.

REM Get current directory
cd /d %~dp0
set CURRENT_DIR=%CD%

echo Working directory: %CURRENT_DIR%
echo.

REM Check if we're in the right directory
if not exist "index.php" (
    echo ERROR: index.php not found. Are you in the correct directory?
    pause
    exit /b 1
)

REM 1. Create necessary directories
echo Creating necessary directories...
if not exist "storage\logs" mkdir "storage\logs"
if not exist "storage\cache" mkdir "storage\cache"
if not exist "storage\sessions" mkdir "storage\sessions"
if not exist "public\uploads" mkdir "public\uploads"
if not exist "public\cache" mkdir "public\cache"
echo [OK] Directories created
echo.

REM 2. Check if .env exists; if not, copy from .env.example
echo Checking .env configuration...
if not exist ".env" (
    if exist ".env.example" (
        copy ".env.example" ".env"
        echo [!] .env created from .env.example
        echo [!] Please edit .env with your cPanel database credentials:
        echo [!] Open: .env with your text editor
        echo.
    ) else (
        echo ERROR: Neither .env nor .env.example found
        pause
        exit /b 1
    )
) else (
    echo [OK] .env already exists
)
echo.

REM 3. Check PHP version
echo Checking PHP version...
php -v 2>nul || (
    echo ERROR: PHP not found in PATH
    echo Please ensure PHP is installed and in your PATH
    pause
    exit /b 1
)
echo.

REM 4. Check Composer
echo Checking Composer...
composer --version 2>nul || (
    echo ERROR: Composer not found in PATH
    echo Please install Composer or request from hosting provider
    pause
    exit /b 1
)
echo.

REM 5. Run Composer install
echo Installing Composer dependencies...
call composer install --no-dev --optimize-autoloader
if errorlevel 1 (
    echo ERROR: Composer install failed
    pause
    exit /b 1
)
echo [OK] Composer install completed
echo.

REM 6. Summary
echo ================================
echo [OK] Setup Complete!
echo ================================
echo.
echo Next Steps:
echo   1. Edit .env with your cPanel database credentials
echo   2. Import database (if not already done)
echo   3. Visit your site in browser: https://yourdomain.com
echo   4. Log into admin: https://yourdomain.com/admin
echo.
echo For detailed instructions, see: CPANEL_DEPLOYMENT.md
echo.
pause
