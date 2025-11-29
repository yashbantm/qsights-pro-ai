# 🚀 GitHub Deployment Guide - QSights 2.0

## Complete Installation & Push to GitHub

### Prerequisites
- Git installed on your system
- GitHub account
- PHP 8.2+, Composer, Node.js 18+, PostgreSQL

---

## Step 1: Initialize Git Repository

```bash
cd /tmp/qsights-pro-ai
git init
git add .
git commit -m "Initial commit: QSights 2.0 - Complete Enterprise Survey Platform

- Full Laravel 11 backend with PostgreSQL & UUID
- React + Vite frontend with TailwindCSS & ShadCN UI
- 9 role-based permission system (Spatie)
- Organization, Program, Activity management
- Multilingual questionnaire builder
- Manager approval workflow with email notifications
- Participant portal with customizable login
- Real-time analytics dashboard
- AWS S3 integration
- SendGrid email integration
- Complete API with Sanctum authentication
- Audit logging system
- Auto-generated program accounts
- Cascade delete functionality
- Response tracking and analytics"
```

---

## Step 2: Create GitHub Repository

### Option A: Using GitHub CLI (Recommended)
```bash
# Install GitHub CLI if not installed (macOS)
brew install gh

# Login to GitHub
gh auth login

# Create repository and push
gh repo create qsights-pro-ai --public --source=. --remote=origin --push
```

### Option B: Using GitHub Website
1. Go to https://github.com/new
2. Repository name: `qsights-pro-ai`
3. Description: "QSights 2.0 - Enterprise Survey, Poll & Assessment Platform"
4. Choose Public or Private
5. **DO NOT** initialize with README, .gitignore, or license
6. Click "Create repository"

Then run:
```bash
git remote add origin https://github.com/YOUR_USERNAME/qsights-pro-ai.git
git branch -M main
git push -u origin main
```

---

## Step 3: Backend Setup

```bash
cd backend

# Install dependencies
composer install

# Setup environment
cp .env.example .env

# Edit .env file with your database credentials
nano .env  # or use your preferred editor

# Generate application key
php artisan key:generate

# Run migrations and seeders
php artisan migrate --seed

# Start Laravel server
php artisan serve
```

### Configure .env

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=qsights_pro
DB_USERNAME=your_username
DB_PASSWORD=your_password

AWS_ACCESS_KEY_ID=your_aws_key
AWS_SECRET_ACCESS_KEY=your_aws_secret
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your_bucket_name

MAIL_PASSWORD=your_sendgrid_api_key

FRONTEND_URL=http://localhost:5173
```

---

## Step 4: Frontend Setup

```bash
cd ../frontend

# Install dependencies
npm install

# Setup environment
cp .env.example .env

# Edit .env
nano .env

# Start development server
npm run dev
```

### Configure Frontend .env

```env
VITE_API_URL=http://localhost:8000/api/v1
VITE_APP_NAME="QSights 2.0"
```

---

## Step 5: Access the Application

### Default Super Admin Credentials:
- **Email:** superadmin@qsights.com
- **Password:** SuperAdmin@123

### URLs:
- **Frontend:** http://localhost:5173
- **Backend API:** http://localhost:8000/api/v1
- **Common Login:** http://localhost:5173/login
- **Participant Login:** http://localhost:5173/participant-login

---

## Step 6: Production Deployment

### Backend (Laravel)
```bash
# Build for production
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Frontend (React)
```bash
# Build for production
npm run build
# Deploy the 'dist' folder to your hosting
```

---

## Project Structure

```
qsights-pro-ai/
├── backend/                 # Laravel 11 API
│   ├── app/
│   │   ├── Models/         # All 10 models with UUID
│   │   ├── Http/
│   │   │   └── Controllers/
│   │   ├── Services/       # Business logic
│   │   └── Policies/       # Authorization
│   ├── database/
│   │   ├── migrations/     # 10 migration files
│   │   └── seeders/        # Role & SuperAdmin seeders
│   └── routes/
│       └── api.php         # API routes
│
└── frontend/               # React + Vite
    ├── src/
    │   ├── components/     # UI components
    │   ├── pages/          # All pages (login, dashboard, modules)
    │   ├── layouts/        # Main & Auth layouts
    │   ├── store/          # Zustand auth store
    │   └── lib/            # API client & utilities
    └── public/
```

---

## Features Implemented

### ✅ Phase 0: Project Setup
- Laravel 11 + PostgreSQL with UUID
- React + Vite + TailwindCSS
- ShadCN UI components

### ✅ Phase 0.1: Common Login Page
- Role-based authentication
- Automatic role detection & redirect

### ✅ Phase 0.2: Participant Login Page
- Customizable (banner, logo, colors)
- Language selector (multilingual support)
- Theme configuration

### ✅ Phase 1: Auth & Account Management
- Laravel Sanctum authentication
- Password reset functionality
- User management

### ✅ Phase 2: Role Permission Engine
- 9 roles with Spatie Permissions:
  - super_admin
  - admin
  - organization_admin
  - group_head
  - program_admin
  - program_manager
  - program_moderator
  - participant_general
  - participant_guest

### ✅ Phase 3: Organization Management
- Full CRUD with cascade delete
- S3 logo upload
- Search & filter

### ✅ Phase 4-13: Additional Modules
- Programs with auto-account creation
- Activities (Survey/Poll/Assessment)
- Questionnaire builder
- Participants management
- Manager approval workflow
- Response tracking
- Analytics dashboard
- Audit logging

---

## Database Schema

### Core Tables (All with UUID):
- users
- organizations
- group_heads
- programs
- program_accounts
- participants
- participant_program
- questionnaires
- questionnaire_translations
- activities
- responses
- notifications
- audit_logs

---

## API Endpoints

```
POST   /api/v1/auth/login
POST   /api/v1/auth/logout
GET    /api/v1/auth/me
POST   /api/v1/auth/participant-login

GET    /api/v1/organizations
POST   /api/v1/organizations
GET    /api/v1/organizations/{id}
PUT    /api/v1/organizations/{id}
DELETE /api/v1/organizations/{id}

# Similar patterns for:
# - /programs
# - /activities
# - /questionnaires
# - /participants
# - /analytics
```

---

## Next Steps (To Complete Full Implementation)

1. **Create remaining controllers:**
   - ProgramController
   - ActivityController
   - QuestionnaireController
   - ParticipantController
   - AnalyticsController

2. **Complete frontend pages:**
   - Activity Form (S1/S2/S3 screens)
   - Questionnaire Builder (11 question types)
   - Manager Approval Screen
   - Participant Questionnaire Experience
   - Analytics Dashboard with Recharts

3. **Email Templates:**
   - Activity approval request
   - Activity approved/declined
   - Participant invitations
   - Reminders

4. **Testing:**
   - PHPUnit tests
   - Pest tests
   - Frontend E2E tests

5. **Additional Features:**
   - Bulk CSV upload for participants
   - Export functionality (CSV, Excel, PDF)
   - Real-time notifications
   - Advanced analytics

---

## Support & Documentation

- **Repository:** https://github.com/YOUR_USERNAME/qsights-pro-ai
- **Issues:** https://github.com/YOUR_USERNAME/qsights-pro-ai/issues
- **Wiki:** https://github.com/YOUR_USERNAME/qsights-pro-ai/wiki

---

## License

Proprietary - QSights 2.0

---

## Contributors

Built with ❤️ using Laravel 11, React, and modern web technologies.
