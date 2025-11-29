# QSightsProAI / QSights 2.0

Enterprise Survey–Poll–Assessment Platform

## 🚀 Features

- **Multi-tenant Organization Management**
- **Program & Group Head Management**
- **Advanced Questionnaire Builder** (11+ question types)
- **Multilingual Support**
- **Activity Management** (Surveys, Polls, Assessments)
- **Manager Approval Workflow**
- **Real-time Analytics & Dashboards**
- **Participant Portal**
- **Role-based Access Control** (9 roles)
- **AWS S3 Integration**
- **Email Notifications** (SendGrid)

## 🏗 Tech Stack

### Backend
- PHP 8.2+ (Laravel 11)
- PostgreSQL with UUID primary keys
- Laravel Sanctum (API authentication)
- Spatie Permissions
- AWS S3 Storage
- SendGrid Email

### Frontend
- React.js + Vite
- TailwindCSS
- ShadCN UI
- React Query
- React Router
- Recharts

## 📋 Prerequisites

- PHP 8.2+
- Composer
- Node.js 18+
- PostgreSQL 14+
- AWS S3 account
- SendGrid account

## 🔧 Installation

### Backend Setup

```bash
cd backend
composer install
cp .env.example .env
# Configure .env with your database and services
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan serve
```

### Frontend Setup

```bash
cd frontend
npm install
cp .env.example .env
# Configure API endpoint
npm run dev
```

## 🔑 Default Credentials

**Super Admin:**
- Email: superadmin@qsights.com
- Password: SuperAdmin@123

## 📁 Project Structure

```
qsights-pro-ai/
├── backend/           # Laravel 11 API
│   ├── app/
│   │   ├── Models/
│   │   ├── Http/
│   │   │   ├── Controllers/
│   │   │   └── Requests/
│   │   ├── Services/
│   │   ├── Repositories/
│   │   └── Policies/
│   └── database/
│       ├── migrations/
│       └── seeders/
└── frontend/          # React + Vite
    ├── src/
    │   ├── components/
    │   ├── pages/
    │   ├── hooks/
    │   ├── lib/
    │   └── layouts/
    └── public/
```

## 🔄 Workflow

1. **Organization Admin** creates Organizations
2. **Group Heads** manage Programs
3. **Program Admin** creates Activities (Surveys/Polls/Assessments)
4. **Manager** approves Activities via email
5. **Participants** complete Activities in their preferred language
6. **Analytics** generated in real-time

## 🌐 API Documentation

API runs at `http://localhost:8000/api/v1`

Key endpoints:
- `/auth/*` - Authentication
- `/organizations/*` - Organization CRUD
- `/programs/*` - Program management
- `/activities/*` - Activity management
- `/questionnaires/*` - Questionnaire builder
- `/participants/*` - Participant management
- `/analytics/*` - Dashboard data

## 📝 License

Proprietary - QSights 2.0

## 🆘 Support

Contact: support@qsights.com

---

Built with ❤️ by QSights Team
