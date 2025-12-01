# 🐳 Docker Quick Start - QSights 2.0

## ✅ Prerequisites
- Docker Desktop installed ([Download here](https://www.docker.com/products/docker-desktop))
- No PHP, PostgreSQL, or Node.js needed!

---

## 🚀 Start the Application (3 Commands)

```bash
# 1. Navigate to project
cd /tmp/qsights-pro-ai

# 2. Start all services (first time takes 5-10 minutes)
docker-compose up -d

# 3. Check if services are running
docker-compose ps
```

---

## 📱 Access the Application

- **Frontend:** http://localhost:5173
- **Backend API:** http://localhost:8000
- **Database:** localhost:5432

**Login Credentials:**
- Email: `superadmin@qsights.com`
- Password: `SuperAdmin@123`

---

## 🔧 Useful Commands

```bash
# View logs
docker-compose logs -f

# Stop services
docker-compose down

# Restart services
docker-compose restart

# Rebuild after code changes
docker-compose up -d --build

# Run migrations manually
docker-compose exec backend php artisan migrate

# Access backend shell
docker-compose exec backend bash

# Access database
docker-compose exec postgres psql -U postgres -d qsights_pro
```

---

## 🐛 Troubleshooting

**If services fail to start:**
```bash
# Clean everything and restart
docker-compose down -v
docker-compose up -d --build
```

**If port 5432/8000/5173 is already in use:**
Edit `docker-compose.yml` and change the port mappings:
```yaml
ports:
  - "5433:5432"  # Change first number only
```

**View backend logs:**
```bash
docker-compose logs backend
```

**Database connection issues:**
```bash
# Check PostgreSQL is healthy
docker-compose exec postgres pg_isready
```

---

## 📊 First Time Setup Complete When You See:

```
✓ postgres (healthy)
✓ backend (running on port 8000)
✓ frontend (running on port 5173)
```

Then open: **http://localhost:5173** and login!

---

## 🎉 That's It!

No need to install PHP, PostgreSQL, Composer, or configure anything manually.
Everything runs in Docker containers.
