@echo off
REM QSights 2.0 - Automated Windows Setup Script
REM This script automates the backend and frontend setup

echo ========================================
echo QSights 2.0 - Automated Setup
echo ========================================
echo.

REM Check if running in correct directory
if not exist "backend\" (
    echo ERROR: Please run this script from the qsights-pro-ai root directory
    echo Current directory: %CD%
    pause
    exit /b 1
)

echo [1/6] Checking prerequisites...
echo.

REM Check PHP
php --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ERROR: PHP is not installed or not in PATH
    echo Please install PHP 8.2+ from https://windows.php.net/download/
    pause
    exit /b 1
)
echo ✓ PHP installed

REM Check Composer
composer --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ERROR: Composer is not installed
    echo Please install from https://getcomposer.org/download/
    pause
    exit /b 1
)
echo ✓ Composer installed

REM Check Node.js
node --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ERROR: Node.js is not installed
    echo Please install from https://nodejs.org/
    pause
    exit /b 1
)
echo ✓ Node.js installed

REM Check PostgreSQL
psql --version >nul 2>&1
if %errorlevel% neq 0 (
    echo WARNING: PostgreSQL CLI not found in PATH
    echo Make sure PostgreSQL is installed and running
)
echo ✓ PostgreSQL check complete
echo.

echo [2/6] Setting up Backend...
cd backend

REM Install Composer dependencies
if not exist "vendor\" (
    echo Installing PHP dependencies...
    composer install --no-interaction
    if %errorlevel% neq 0 (
        echo ERROR: Composer install failed
        pause
        exit /b 1
    )
) else (
    echo ✓ Dependencies already installed
)

REM Copy .env if doesn't exist
if not exist ".env" (
    echo Creating .env file...
    copy .env.example .env
) else (
    echo ✓ .env file exists
)

REM Generate app key if not set
findstr /C:"APP_KEY=" .env | findstr /C:"base64:" >nul
if %errorlevel% neq 0 (
    echo Generating application key...
    php artisan key:generate
) else (
    echo ✓ Application key already set
)

echo.
echo [3/6] Database Setup...
echo Please ensure:
echo   - PostgreSQL is running
echo   - Database 'qsights_pro' exists
echo   - User 'postgres' can access it
echo.
echo If database doesn't exist, create it with:
echo   psql -U postgres -c "CREATE DATABASE qsights_pro;"
echo.
pause

REM Run migrations
echo Running database migrations...
php artisan migrate --seed --force
if %errorlevel% neq 0 (
    echo ERROR: Migration failed
    echo Check your database connection in .env file
    pause
    exit /b 1
)
echo ✓ Database migrated and seeded
echo.

cd ..

echo [4/6] Setting up Frontend...
cd frontend

REM Install npm dependencies
if not exist "node_modules\" (
    echo Installing Node dependencies (this may take a few minutes)...
    call npm install
    if %errorlevel% neq 0 (
        echo ERROR: npm install failed
        pause
        exit /b 1
    )
) else (
    echo ✓ Node modules already installed
)

REM Copy frontend .env
if not exist ".env" (
    echo Creating frontend .env file...
    copy .env.example .env
) else (
    echo ✓ Frontend .env exists
)

cd ..
echo.

echo [5/6] Setup Complete!
echo.
echo ========================================
echo QSights 2.0 is ready to run!
echo ========================================
echo.
echo Backend: http://localhost:8000
echo Frontend: http://localhost:5173
echo.
echo Login Credentials:
echo   Email: superadmin@qsights.com
echo   Password: SuperAdmin@123
echo.

echo [6/6] Starting servers...
echo.
echo Opening TWO new terminal windows:
echo   1. Backend server (Laravel)
echo   2. Frontend server (React)
echo.
echo Press any key to start the servers...
pause >nul

REM Start backend server in new window
start "QSights Backend" cmd /k "cd backend && php artisan serve"

REM Wait 2 seconds
timeout /t 2 /nobreak >nul

REM Start frontend server in new window
start "QSights Frontend" cmd /k "cd frontend && npm run dev"

echo.
echo ✓ Servers started!
echo.
echo Wait 10-15 seconds for servers to initialize, then:
echo   1. Open http://localhost:5173 in your browser
echo   2. Login with the credentials above
echo.
echo To stop servers: Close the terminal windows or press Ctrl+C
echo.
pause
