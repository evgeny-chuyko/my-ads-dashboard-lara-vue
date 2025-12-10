# MyAds Dashboard 🚀

> Full-featured advertising application management platform with role-based access control, analytics, and event-driven architecture

[![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?logo=laravel)](https://laravel.com)
[![Vue.js](https://img.shields.io/badge/Vue.js-3-4FC08D?logo=vue.js)](https://vuejs.org)
[![TypeScript](https://img.shields.io/badge/TypeScript-5-3178C6?logo=typescript)](https://www.typescriptlang.org)
[![Docker](https://img.shields.io/badge/Docker-Ready-2496ED?logo=docker)](https://www.docker.com)
[![Tests](https://img.shields.io/badge/Tests-Passing-success)](https://github.com)

## ✨ Features

- 🔐 **Authentication**: Token-based auth with Laravel Sanctum
- 👥 **RBAC**: Admin and Publisher roles with different access rights
- 📊 **Analytics**: Integration with Google Analytics, Mixpanel, Custom Webhooks
- 🎯 **Event-Driven**: Asynchronous event processing via queues
- 🧪 **Testing**: 100+ Feature and Unit tests
- 🎨 **Modern UI**: Responsive design with Tailwind CSS
- 🏗️ **Clean Architecture**: Service Layer + Event-Driven Pattern
- 🔒 **Security**: Policies, Middleware, Validation, Audit Logs
- 📱 **API-First**: RESTful API with full Swagger documentation
- 📝 **API Documentation**: Swagger UI with OpenAPI specification

## 🏗 Architecture

The project uses **Service Layer + Event-Driven Pattern** to demonstrate modern architectural approaches:

### Backend (Laravel)
```
Controller → Service → Model → Database
     ↓
   Event → Listener (Queue) → Analytics Providers
```

**Main layers:**
- **Controllers**: Single Action Controllers (`__invoke`) for clear separation of responsibilities
- **Services**: Business logic (AuthService, AppService, AdminService, AnalyticsService)
- **Events**: Domain events (UserLoggedIn, AppCreated, AppUpdated, etc.)
- **Listeners**: Asynchronous event handlers via queues
- **Models**: Eloquent models with relationships and business methods
- **Policies**: Authorization logic (AppPolicy)
- **Middleware**: RBAC and user status verification
- **Resources**: API Resources for response formatting
- **Requests**: Form Requests for validation

### Frontend (Vue.js)
```
View → Store (Pinia) → API Client → Backend
```
- **Views**: Vue components with Composition API
- **Stores**: Pinia for state management
- **API Clients**: Axios with interceptors
- **Router**: Vue Router with guards

## 🛠 Technology Stack

### Backend
- **PHP 8.3**
- **Laravel 11**
- **PostgreSQL 16**
- **Laravel Sanctum** - API authentication
- **Laravel Queue** - Asynchronous task processing
- **Swagger/OpenAPI** - API documentation
- **PHPUnit** - Testing framework

### Frontend
- **Vue.js 3** - Progressive JavaScript framework
- **TypeScript 5** - Type-safe JavaScript
- **Vite 7** - Next generation frontend tooling
- **Pinia** - Intuitive state management
- **Vue Router 4** - Official router
- **Tailwind CSS 3** - Utility-first CSS framework
- **Axios** - Promise-based HTTP client

### Analytics Integration
- **Google Analytics 4** - Web analytics
- **Mixpanel** - Product analytics
- **Custom Webhooks** - Flexible integration

### DevOps
- **Docker & Docker Compose** - Containerization
- **Nginx** - Web server
- **PHP-FPM** - FastCGI Process Manager
- **Makefile** - Automation commands

## 🚀 Quick Start

### Requirements
- Docker Desktop (or Docker Engine + Docker Compose)
- Git

### Installation and Setup

```bash
# 1. Clone repository
git clone <repository-url>
cd my-ads-dashboard-lara-vue

# 2. Start Docker containers
make up
# or
docker-compose up -d

# 3. Install dependencies and configure project
make install

# 4. Run migrations and seeders
make migrate
make seed

# 5. (Optional) Start queue worker for analytics
make queue-work
```

### Application Access

- 🌐 **Frontend**: http://localhost:5173
- 🔌 **Backend API**: http://localhost/api
- 📚 **API Documentation**: http://localhost/api/documentation
- 🗄️ **PostgreSQL**: localhost:5432

### Makefile Commands

```bash
make help           # Show all available commands
make up             # Start containers
make down           # Stop containers
make logs           # Show logs
make test           # Run all tests
make queue-work     # Start queue worker
```

### 🔑 Test Accounts

**Administrator:**
```
Email: admin@myads.com
Password: password
```
- ✅ Full system access
- ✅ User management
- ✅ View all applications
- ✅ Global statistics

**Publisher:**
```
Email: publisher@myads.com
Password: password
```
- ✅ Manage own applications
- ✅ View statistics
- ✅ CRUD operations

**Publisher 2:**
```
Email: publisher2@myads.com
Password: password
```

## 📁 Project Structure

```
my-ads-dashboard-lara-vue/
├── backend/                      # Laravel Backend
│   ├── app/
│   │   ├── Enums/               # PHP 8.3 Enums (UserStatus, AppStatus, AuditAction)
│   │   ├── Models/              # Eloquent Models (User, Role, App, AuditLog)
│   │   ├── Services/            # Business Logic Services
│   │   │   ├── Auth/            # AuthService
│   │   │   ├── App/             # AppService  
│   │   │   ├── Admin/           # AdminService
│   │   │   ├── Audit/           # AuditService
│   │   │   └── Analytics/       # AnalyticsService + Providers
│   │   ├── Events/              # Domain Events (6 events)
│   │   ├── Listeners/           # Event Listeners (6 listeners)
│   │   ├── Http/
│   │   │   ├── Controllers/API/ # Single Action Controllers
│   │   │   │   ├── Auth/        # Register, Login, Logout, Me
│   │   │   │   ├── Publisher/   # AppController (CRUD)
│   │   │   │   └── Admin/       # User, App, Stats, Audit
│   │   │   ├── Requests/        # Form Requests (validation)
│   │   │   ├── Resources/       # API Resources (formatting)
│   │   │   └── Middleware/      # Custom Middleware (RBAC)
│   │   ├── Policies/            # Authorization Policies
│   │   └── Providers/           # Service Providers
│   ├── database/
│   │   ├── migrations/          # Database Migrations
│   │   ├── seeders/             # Data Seeders
│   │   └── factories/           # Model Factories
│   ├── tests/                   # PHPUnit Tests
│   │   ├── Feature/             # Feature Tests (80+ tests)
│   │   │   ├── Auth/            # Auth tests
│   │   │   ├── Publisher/       # Publisher tests
│   │   │   └── Admin/           # Admin tests
│   │   ├── Unit/                # Unit Tests
│   │   ├── Traits/              # Test Helpers
│   │   └── README.md            # Testing Guide
│   ├── config/
│   │   └── analytics.php        # Analytics Configuration
│   ├── routes/
│   │   ├── api.php              # API Routes (16 endpoints)
│   │   └── web.php              # Web Routes
│   ├── ANALYTICS.md             # Analytics Documentation
│   └── .env.example             # Environment Template
│
├── frontend/                     # Vue.js Frontend
│   ├── src/
│   │   ├── api/                 # API Clients (auth, apps, admin)
│   │   ├── stores/              # Pinia Stores (auth, apps, admin)
│   │   ├── types/               # TypeScript Types
│   │   ├── views/               # Vue Views
│   │   │   ├── auth/            # Login, Register
│   │   │   ├── publisher/       # Apps Management
│   │   │   └── admin/           # Admin Dashboard
│   │   ├── router/              # Vue Router
│   │   └── App.vue              # Root Component
│   ├── vite.config.ts           # Vite Configuration
│   ├── tailwind.config.js       # Tailwind Configuration
│   ├── postcss.config.js        # PostCSS Configuration
│   └── package.json             # Dependencies
│
├── docker/                       # Docker Configuration
│   ├── nginx/                   # Nginx Config
│   ├── php/                     # PHP-FPM Config
│   └── postgres/                # PostgreSQL Init
│
├── Makefile                      # Automation Commands
├── docker-compose.yml            # Docker Compose
├── README.md                     # This File
```

## 🔑 Main Features

### 👤 For Publisher
- ✅ Registration and authentication
- ✅ Application management (Create, Read, Update, Delete)
- ✅ Change application status (Active, Paused, Archived)
- ✅ View application statistics
- ✅ Track impressions

### 👨‍💼 For Administrator
- ✅ View all users
- ✅ View all applications
- ✅ Ban/unban users
- ✅ Global platform statistics
- ✅ View audit logs
- ✅ System management

## 📡 API Endpoints

### Authentication
```
POST   /api/auth/register       # Registration
POST   /api/auth/login          # Login
POST   /api/auth/logout         # Logout (auth required)
GET    /api/auth/me             # Current user (auth required)
```

### Publisher (Authenticated)
```
GET    /api/apps                # List applications
POST   /api/apps                # Create application
GET    /api/apps/{id}           # View application
PUT    /api/apps/{id}           # Update application
DELETE /api/apps/{id}           # Delete application
GET    /api/apps/{id}/stats     # Application statistics
```

### Admin (Admin Role Required)
```
GET    /api/admin/users         # All users
POST   /api/admin/users/{id}/ban    # Ban user
POST   /api/admin/users/{id}/unban  # Unban user
GET    /api/admin/apps          # All applications
GET    /api/admin/stats         # Global statistics
GET    /api/admin/audit-logs    # Audit logs
```

## 🧪 Testing

The project includes **100+ tests** covering all main features.

### Running tests via Makefile

```bash
make test              # All tests
make test-feature      # Feature tests
make test-unit         # Unit tests
make test-auth         # Authentication tests
make test-publisher    # Publisher tests
make test-admin        # Admin tests
make test-coverage     # With coverage report
make test-parallel     # Parallel execution
```

### Test Structure

```
tests/
├── Feature/
│   ├── Auth/              # 4 tests (Register, Login, Logout, Me)
│   ├── Publisher/         # 6 tests (Apps CRUD + Stats)
│   └── Admin/             # 4 tests (Users, Apps, Stats, Audit)
├── Unit/                  # Unit tests
├── Traits/
│   └── TestHelpers.php    # Helper methods
└── README.md              # Testing documentation
```

### Manual API Testing

```bash
# Login
curl -X POST http://localhost/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"publisher@myads.com","password":"password"}'

# Get applications (replace TOKEN)
curl -X GET http://localhost/api/apps \
  -H "Authorization: Bearer YOUR_TOKEN"
```

## 📊 Analytics and Events

The project uses **Event-Driven Architecture** to send events to analytics systems.

### Supported Providers

- **Google Analytics 4** - Web analytics
- **Mixpanel** - Product analytics  
- **Custom Webhooks** - Any external systems

### Tracked Events

**Auth Events:**
- `User Registered` - user registration
- `User Logged In` - login (with IP and User Agent)
- `User Logged Out` - logout

**App Events:**
- `App Created` - application creation
- `App Updated` - update (with changes)
- `App Deleted` - deletion

### Analytics Setup

```bash
# 1. Add credentials to .env
GOOGLE_ANALYTICS_MEASUREMENT_ID=G-XXXXXXXXXX
GOOGLE_ANALYTICS_API_SECRET=your_secret
MIXPANEL_TOKEN=your_token
ANALYTICS_WEBHOOK_URL=https://your-endpoint.com/webhook

# 2. Start queue worker
make queue-work

# 3. Events will be sent asynchronously
```

More details: `backend/ANALYTICS.md`

## 🔧 Development

### Backend Commands
```bash
# Enter PHP container
docker exec -it myads_php sh

# Create migration
php artisan make:migration create_table_name

# Create model
php artisan make:model Example -mfs

# Create controller
php artisan make:controller API/ControllerName --invokable

# Create service
php artisan make:class Services/ServiceName

# Create event
php artisan make:event EventName

# Create listener
php artisan make:listener ListenerName --event=EventName

# Update Swagger documentation
php artisan l5-swagger:generate
```

### Frontend Commands
```bash
# Enter Node container
docker exec -it myads_node sh

# Start dev server
npm run dev

# Build for production
npm run build

# Check TypeScript types
npm run type-check

# Lint
npm run lint
```

### Docker Commands
```bash
# Start all containers
docker-compose up -d

# Stop all containers
docker-compose down

# View logs
docker-compose logs -f

# Restart container
docker-compose restart myads_php

# Rebuild containers
docker-compose up -d --build
```

## 🎯 What the Project Demonstrates

### Backend Skills
- ✅ **Laravel 11** best practices
- ✅ **Service Layer Pattern** - clean architecture
- ✅ **Event-Driven Architecture** - asynchronous processing
- ✅ **Single Action Controllers** - `__invoke` pattern
- ✅ **RESTful API** design with Swagger documentation
- ✅ **Database design** - relationships, migrations, seeders
- ✅ **Authentication** - Laravel Sanctum (token-based)
- ✅ **Authorization** - Policies for RBAC
- ✅ **Validation** - Form Requests
- ✅ **API Resources** - response formatting
- ✅ **Middleware** - custom middleware for RBAC
- ✅ **PHP 8.3 Enums** - typed constants
- ✅ **Queue System** - asynchronous tasks
- ✅ **Testing** - Feature & Unit tests (100+)
- ✅ **Analytics Integration** - Google Analytics, Mixpanel, Webhooks

### Frontend Skills
- ✅ **Vue 3 Composition API** - modern approach
- ✅ **TypeScript** - type-safe code
- ✅ **Pinia** - state management
- ✅ **Vue Router** - navigation with guards
- ✅ **Axios** - HTTP client with interceptors
- ✅ **Tailwind CSS** - utility-first styling
- ✅ **Responsive design** - adaptive design
- ✅ **Component architecture** - reusable components

### DevOps & Tools
- ✅ **Docker & Docker Compose** - containerization
- ✅ **Multi-container setup** - Nginx, PHP-FPM, PostgreSQL, Node
- ✅ **Makefile** - command automation
- ✅ **Environment configuration** - .env management
- ✅ **Database migrations** - DB versioning
- ✅ **Seeding** - test data
- ✅ **Git** - version control

## 📊 Project Statistics

### Code Metrics
- **Backend Files**: 60+
- **Frontend Files**: 25+
- **Total Lines of Code**: ~5000+
- **Tests**: 100+ (Feature + Unit)

### API & Database
- **API Endpoints**: 16
- **Database Tables**: 5 (users, roles, apps, audit_logs, jobs)
- **Migrations**: 10+
- **Seeders**: 4

### Architecture Components
- **Services**: 5 (Auth, App, Admin, Audit, Analytics)
- **Controllers**: 10 (Single Action Controllers)
- **Events**: 6 (UserRegistered, UserLoggedIn, UserLoggedOut, AppCreated, AppUpdated, AppDeleted)
- **Listeners**: 6 (Analytics listeners)
- **Policies**: 1 (AppPolicy)
- **Middleware**: 2 (EnsureUserIsAdmin, EnsureUserNotBanned)
- **Analytics Providers**: 3 (Google Analytics, Mixpanel, Custom Webhook)

### Frontend
- **Views**: 7+
- **Stores**: 3 (auth, apps, admin)
- **API Clients**: 3

## 📚 Documentation

### Main Documentation
- **README.md** (this file) - Project overview

### Backend
- **backend/tests/README.md** - Testing documentation

## 🤝 Contributing

This project was created for educational purposes to demonstrate Full-Stack development skills.

## 👨‍💻 Author

Project created to demonstrate Full-Stack development skills with modern technology stack and clean architecture.

## 🌟 Key Implementation Features

### 1. **Single Action Controllers**
**Single Action Controllers** use the `__invoke` method, making each controller responsible for only one action:
```php
class LoginController extends Controller {
    public function __invoke(LoginRequest $request) { }
}
```
Benefits: clear separation of responsibilities, easy testing, simple to understand.

### 2. **Event-Driven Architecture**
Events are dispatched at key points in the application:
```php
event(new UserLoggedIn($user, $request->ip(), $request->userAgent()));
// → Queue → Listener → Analytics Providers
```
Listeners process events asynchronously via queues (`ShouldQueue`).

### 3. **Service Layer Pattern**
Business logic is encapsulated in services:
```php
Controller → AuthService → Model → Database
```
Benefits: logic reusability, easy testing, clean controllers.

### 4. **Comprehensive Testing**
100+ tests cover all main scenarios:
```bash
make test              # All tests
make test-coverage     # With coverage
```

### 5. **Multi-Provider Analytics**
Flexible analytics system with support for multiple providers:
- Google Analytics 4
- Mixpanel
- Custom Webhooks

### 6. **Type-Safe Frontend**
TypeScript + Vue 3 Composition API for type safety
