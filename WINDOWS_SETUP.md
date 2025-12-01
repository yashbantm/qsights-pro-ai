# 🪟 Windows Local Setup Guide - QSights 2.0

## Prerequisites Installation

### 1️⃣ Install PHP 8.2+ (Windows)
Download from: https://windows.php.net/download/
- Download **PHP 8.2 Thread Safe (x64)**
- Extract to `C:\php`
- Add `C:\php` to System PATH
- Rename `php.ini-development` to `php.ini`
- Enable extensions in `php.ini`:
  ```ini
  extension=pdo_pgsql
  extension=pgsql
  extension=mbstring
  extension=openssl
  extension=fileinfo
  ```

**Verify:**
```cmd
php --version
```

### 2️⃣ Install Composer
Download from: https://getcomposer.org/download/
- Run `Composer-Setup.exe`
- Follow installation wizard

**Verify:**
```cmd
composer --version
```

### 3️⃣ Install PostgreSQL 14+
Download from: https://www.postgresql.org/download/windows/
- Run installer
- Set password: `Qsights@123`
- Port: `5432`
- Remember to add `C:\Program Files\PostgreSQL\14\bin` to PATH

**Create Database:**
```cmd
psql -U postgres
CREATE DATABASE qsights_pro;
\q
```

### 4️⃣ Install Node.js 18+
Download from: https://nodejs.org/
- Download LTS version
- Run installer

**Verify:**
```cmd
node --version
npm --version
```

---

## 🚀 Quick Setup (Copy-Paste Commands)

### Clone Repository
```cmd
cd C:\Users\%USERNAME%\Desktop
git clone https://github.com/yashbantm/qsights-pro-ai.git
cd qsights-pro-ai
```

---

## 🔧 Backend Setup

```cmd
cd backend

REM Install dependencies
composer install

REM Copy environment file
copy .env.example .env

REM Generate application key
php artisan key:generate

REM Run migrations and seed SuperAdmin
php artisan migrate --seed

REM Start Laravel server (keep this terminal open)
php artisan serve
```

**✅ Backend running at:** http://127.0.0.1:8000

---

## 🎨 Frontend Setup (Open NEW Terminal)

```cmd
cd C:\Users\%USERNAME%\Desktop\qsights-pro-ai\frontend

REM Install dependencies
npm install

REM Start development server
npm run dev
```

**✅ Frontend running at:** http://localhost:5173

---

## 🔑 Login Credentials

**URL:** http://localhost:5173/login

**SuperAdmin Account:**
- **Email:** `superadmin@qsights.com`
- **Password:** `SuperAdmin@123`

---

## 📝 Manual .env Configuration

Edit `backend\.env` file:

```env
APP_NAME="QSights 2.0"
APP_ENV=local
APP_KEY=base64:WILL_BE_GENERATED_BY_php_artisan_key:generate
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=qsights_pro
DB_USERNAME=postgres
DB_PASSWORD=Qsights@123

FRONTEND_URL=http://localhost:5173
```

---

## 🐛 Troubleshooting

### Issue: "could not find driver"
**Solution:** Enable PostgreSQL extensions in `php.ini`:
```ini
extension=pdo_pgsql
extension=pgsql
```
Restart terminal.

### Issue: "Class 'PDO' not found"
**Solution:** Enable in `php.ini`:
```ini
extension=pdo
```

### Issue: Port 8000 already in use
**Solution:** Use different port:
```cmd
php artisan serve --port=8080
```
Update frontend `.env`: `VITE_API_URL=http://localhost:8080/api/v1`

### Issue: Database connection failed
**Solution:**
1. Check PostgreSQL is running: `pg_isready`
2. Verify credentials in `.env`
3. Create database if missing:
```cmd
psql -U postgres -c "CREATE DATABASE qsights_pro;"
```

### Issue: CORS errors
**Solution:** Frontend `.env` should have:
```env
VITE_API_URL=http://localhost:8000/api/v1
```

---

## 🔄 Running Multiple Times

After first setup, just start both servers:

**Terminal 1 - Backend:**
```cmd
cd C:\Users\%USERNAME%\Desktop\qsights-pro-ai\backend
php artisan serve
```

**Terminal 2 - Frontend:**
```cmd
cd C:\Users\%USERNAME%\Desktop\qsights-pro-ai\frontend
npm run dev
```

---

## ✅ Success Checklist

- [ ] PHP 8.2+ installed
- [ ] Composer installed
- [ ] PostgreSQL 14+ installed
- [ ] Node.js 18+ installed
- [ ] Database `qsights_pro` created
- [ ] Backend dependencies installed (`vendor/` folder exists)
- [ ] Frontend dependencies installed (`node_modules/` folder exists)
- [ ] `.env` file configured
- [ ] Migrations run successfully
- [ ] Backend server running on port 8000
- [ ] Frontend server running on port 5173
- [ ] Can access http://localhost:5173
- [ ] Can login with SuperAdmin credentials

---

## 📱 What You'll See

When everything is working:
1. Frontend loads at http://localhost:5173
2. Login page appears
3. Enter: `superadmin@qsights.com` / `SuperAdmin@123`
4. Redirects to Dashboard
5. Can navigate through Organizations, Programs, Activities, etc.

---

## 🆘 Still Having Issues?

Check backend logs:
```cmd
cd backend
tail -f storage/logs/laravel.log
```

Check if API is responding:
```cmd
curl http://localhost:8000/api/v1/auth/me
```

Verify database connection:
```cmd
cd backend
php artisan tinker
DB::connection()->getPdo();
```

---

**Need Docker instead?** See `DOCKER_START.md` for containerized setup.
