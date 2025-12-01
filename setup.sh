#!/bin/bash
# QSights 2.0 - Automated macOS/Linux Setup Script

set -e  # Exit on error

echo "========================================"
echo "QSights 2.0 - Automated Setup"
echo "========================================"
echo ""

# Check if running in correct directory
if [ ! -d "backend" ]; then
    echo "ERROR: Please run this script from the qsights-pro-ai root directory"
    echo "Current directory: $(pwd)"
    exit 1
fi

echo "[1/6] Checking prerequisites..."
echo ""

# Check PHP
if ! command -v php &> /dev/null; then
    echo "ERROR: PHP is not installed"
    echo "Install with: brew install php@8.2"
    exit 1
fi
echo "✓ PHP installed: $(php --version | head -n 1)"

# Check Composer
if ! command -v composer &> /dev/null; then
    echo "ERROR: Composer is not installed"
    echo "Install with: brew install composer"
    exit 1
fi
echo "✓ Composer installed"

# Check Node.js
if ! command -v node &> /dev/null; then
    echo "ERROR: Node.js is not installed"
    echo "Install with: brew install node"
    exit 1
fi
echo "✓ Node.js installed: $(node --version)"

# Check PostgreSQL
if ! command -v psql &> /dev/null; then
    echo "WARNING: PostgreSQL CLI not found"
    echo "Install with: brew install postgresql@14"
fi
echo "✓ PostgreSQL check complete"
echo ""

echo "[2/6] Setting up Backend..."
cd backend

# Install Composer dependencies
if [ ! -d "vendor" ]; then
    echo "Installing PHP dependencies..."
    composer install --no-interaction
else
    echo "✓ Dependencies already installed"
fi

# Copy .env if doesn't exist
if [ ! -f ".env" ]; then
    echo "Creating .env file..."
    cp .env.example .env
else
    echo "✓ .env file exists"
fi

# Generate app key if not set
if ! grep -q "APP_KEY=base64:" .env; then
    echo "Generating application key..."
    php artisan key:generate
else
    echo "✓ Application key already set"
fi

echo ""
echo "[3/6] Database Setup..."
echo "Please ensure:"
echo "  - PostgreSQL is running"
echo "  - Database 'qsights_pro' exists"
echo "  - User 'postgres' can access it"
echo ""
echo "If database doesn't exist, create it with:"
echo "  createdb qsights_pro"
echo ""
read -p "Press Enter to continue..."

# Run migrations
echo "Running database migrations..."
php artisan migrate --seed --force
echo "✓ Database migrated and seeded"
echo ""

cd ..

echo "[4/6] Setting up Frontend..."
cd frontend

# Install npm dependencies
if [ ! -d "node_modules" ]; then
    echo "Installing Node dependencies (this may take a few minutes)..."
    npm install
else
    echo "✓ Node modules already installed"
fi

# Copy frontend .env
if [ ! -f ".env" ]; then
    echo "Creating frontend .env file..."
    cp .env.example .env
else
    echo "✓ Frontend .env exists"
fi

cd ..
echo ""

echo "[5/6] Setup Complete!"
echo ""
echo "========================================"
echo "QSights 2.0 is ready to run!"
echo "========================================"
echo ""
echo "Backend: http://localhost:8000"
echo "Frontend: http://localhost:5173"
echo ""
echo "Login Credentials:"
echo "  Email: superadmin@qsights.com"
echo "  Password: SuperAdmin@123"
echo ""

echo "[6/6] Starting servers..."
echo ""
echo "Opening TWO new terminal windows:"
echo "  1. Backend server (Laravel)"
echo "  2. Frontend server (React)"
echo ""
read -p "Press Enter to start the servers..."

# Start backend server in new terminal
if [[ "$OSTYPE" == "darwin"* ]]; then
    # macOS
    osascript -e 'tell app "Terminal" to do script "cd '"$(pwd)"'/backend && php artisan serve"'
    sleep 2
    osascript -e 'tell app "Terminal" to do script "cd '"$(pwd)"'/frontend && npm run dev"'
else
    # Linux
    gnome-terminal -- bash -c "cd backend && php artisan serve; exec bash" &
    sleep 2
    gnome-terminal -- bash -c "cd frontend && npm run dev; exec bash" &
fi

echo ""
echo "✓ Servers started!"
echo ""
echo "Wait 10-15 seconds for servers to initialize, then:"
echo "  1. Open http://localhost:5173 in your browser"
echo "  2. Login with the credentials above"
echo ""
echo "To stop servers: Close the terminal windows or press Ctrl+C"
echo ""
