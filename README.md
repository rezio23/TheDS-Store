# The DS — Premium Multi-Brand Clothing E-Commerce

A full-featured e-commerce web application for **The DS**, a premium multi-brand clothing store based in Phnom Penh, Cambodia. Built with Laravel 11, it offers a complete shopping experience with an AI-powered customer support chatbot, Bakong KHQR payment integration, Telegram order notifications, a custom admin dashboard, and a promotions engine.

**Live URL (Local):** `http://localhost:8080/theDS-Site/public`

---

## Table of Contents

- [Features](#features)
- [Tech Stack](#tech-stack)
- [Project Structure](#project-structure)
- [Installation](#installation)
- [Configuration](#configuration)
- [Database](#database)
- [Routes & API Endpoints](#routes--api-endpoints)
- [AI Chatbot (DS Assistant)](#ai-chatbot-ds-assistant)
- [Payment Integration (Bakong KHQR)](#payment-integration-bakong-khqr)
- [Telegram Notifications](#telegram-notifications)
- [Admin Dashboard](#admin-dashboard)
- [Development](#development)
- [Testing](#testing)
- [Security](#security)
- [Legacy Version](#legacy-version)

---

## Features

### Storefront
- **Product Catalog** — Browse premium brands (Nike, Polo/Ralph Lauren, Adidas, Puma, Balenciaga, Gucci, Prada, Chanel, etc.) across categories: clothes, sneakers, bags, perfumes, and accessories.
- **Product Detail** — Image gallery, size selection, brand badges, ratings, stock status.
- **Shopping Cart** — Session-based cart with add, update quantity, and remove functionality.
- **Favorites** — Authenticated users can save products to their favorites list.
- **Promotions & Coupons** — Apply percentage or fixed-amount discount codes at checkout with minimum order thresholds, usage limits, and expiry dates.
- **Checkout Flow** — Multi-step checkout with shipping information collection and payment processing.
- **Notifications** — In-app notification system for order updates and admin announcements.
- **Help Center** — Contact form for customer inquiries with file attachment support.

### AI Customer Support
- **DS Assistant** — Embeddable AI chatbot widget powered by Groq API (Llama 3.3 70B).
- Maintains session conversation history (up to 20 messages).
- Accessible via a floating chat bubble on every page.

### Payments
- **Debit Card Checkout** — Standard card payment flow.
- **Bakong KHQR** — Cambodia's national QR code payment standard integration for cashless transactions.

### Admin Panel
- **Dashboard Analytics** — Revenue charts, order statistics, user growth, top products.
- **Order Management** — View orders, update status, track revenue.
- **Product CRUD** — Add, edit, delete products with image upload or external URL support.
- **Category Management** — Manage product categories.
- **User Requests** — Handle help center submissions.
- **Announcements** — Send in-app notifications to all users.
- **Promotions Management** — Create and monitor promo codes with usage analytics.
- **Reporting** — Date-range filtered reports (today, 7d, 30d, month, year, all time).

### Notifications
- **Telegram Bot** — Real-time order alerts sent to a configured Telegram channel/bot when orders are placed.
- **In-App Notifications** — Users receive notifications for orders and announcements.

---

## Tech Stack

### Core
| Technology | Version | Purpose |
|------------|---------|---------|
| PHP | ^8.2 | Server-side language |
| Laravel | ^11.0 | MVC framework |
| Node.js | >= 18 | JavaScript runtime (chatbot & build tools) |

### Backend Dependencies
| Package | Version | Purpose |
|---------|---------|---------|
| `laravel/framework` | ^11.0 | Core framework |
| `laravel/breeze` | ^2.0 | Authentication scaffolding |
| `laravel/tinker` | ^2.9 | Interactive REPL |
| `filament/filament` | ^3.2 | Admin panel framework (installed, not actively used) |
| `khqr-gateway/bakong-khqr-php` | ^1.0 | Bakong KHQR payment generation |

### Frontend Dependencies
| Package | Version | Purpose |
|---------|---------|---------|
| `vite` | ^8.0.0 | Build tool & dev server |
| `laravel-vite-plugin` | ^3.1 | Vite-Laravel integration |
| `tailwindcss` | ^3.1.0 | Utility-first CSS framework |
| `@tailwindcss/forms` | ^0.5.2 | Form element resets |
| `@tailwindcss/vite` | ^4.0.0 | Tailwind Vite plugin |
| `alpinejs` | ^3.4.2 | Lightweight reactive JS framework |
| `axios` | ^1.16.0 | HTTP client |
| `autoprefixer` | ^10.4.2 | CSS vendor prefixing |
| `postcss` | ^8.4.31 | CSS transformations |

### Frontend CDN Libraries
| Library | Version | Purpose |
|---------|---------|---------|
| jQuery | 3.7.1 | DOM manipulation & AJAX |
| Lucide Icons | latest | SVG icon library |
| Chart.js | latest | Admin dashboard charts |
| Google Fonts | — | Doto, Krona One, Modak |

### AI Chatbot
| Technology | Version | Purpose |
|------------|---------|---------|
| Node.js | >= 18 | Runtime |
| Express | ^4.22.2 | HTTP server |
| `groq-sdk` | ^0.8.0 | Groq AI API client |
| `cors` | ^2.8.6 | Cross-origin requests |
| `dotenv` | ^16.6.1 | Environment variables |

### Database
| Technology | Purpose |
|------------|---------|
| MySQL | Primary database (recommended for production) |
| SQLite | Local development & legacy version |

### External APIs & Services
| Service | Purpose |
|---------|---------|
| Groq API | AI inference (Llama 3.3 70B) |
| Telegram Bot API | Order notification alerts |
| Bakong KHQR | Cambodia national QR payments |
| api.qrserver.com | QR code image rendering |

### Development Tools
| Tool | Purpose |
|------|---------|
| PHPUnit | Testing framework |
| Laravel Pint | Code style fixer |
| Laravel Pail | Real-time log monitoring |
| Concurrently | Run multiple dev servers |
| Faker | Test data generation |
| Mockery | Mocking framework |

---

## Project Structure

```
theDS-Site/
├── .env                          # Environment variables
├── .env.example                  # Environment template
├── artisan                       # Laravel CLI
├── composer.json / composer.lock  # PHP dependencies
├── package.json / package-lock.json # Node.js dependencies
├── server.js                     # Node.js Express chatbot server
├── vite.config.js                # Vite configuration
├── tailwind.config.js            # TailwindCSS configuration
├── postcss.config.js             # PostCSS configuration
├── phpunit.xml                   # PHPUnit test configuration
├── chatbot-README.md            # Chatbot-specific documentation
├── .htaccess                     # Apache redirect to public/
│
├── app/
│   ├── helpers.php              # Custom helper functions (storage_url)
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/           # AdminAuthController, DashboardController
│   │   │   ├── CartController.php
│   │   │   ├── ChatController.php
│   │   │   ├── CheckoutController.php
│   │   │   ├── FavoriteController.php
│   │   │   ├── HomeController.php
│   │   │   ├── NotificationController.php
│   │   │   ├── PageController.php
│   │   │   ├── ProductController.php
│   │   │   ├── ProfileController.php
│   │   │   └── ShopController.php
│   │   └── Middleware/
│   │       ├── EnsureIsAdmin.php
│   │       └── SecurityHeadersMiddleware.php
│   ├── Models/                   # User, Product, Order, OrderItem, Category, Favorite, Promotion, Notification, UserRequest
│   ├── Services/
│   │   └── TelegramService.php
│   └── Providers/
│       └── AppServiceProvider.php
│
├── bootstrap/                    # Laravel bootstrap files
├── config/                       # Laravel configuration files
├── database/
│   ├── database.sqlite           # SQLite database (if used)
│   ├── factories/                # Model factories
│   ├── migrations/               # Database migrations (15+)
│   └── seeders/                  # Database seeders
│
├── legacy/                       # Legacy plain-PHP version of the site
│   ├── index.php
│   ├── pages/
│   ├── admin/
│   ├── database/database.sqlite
│   └── includes/
│
├── public/                       # Web server document root
│   ├── index.php
│   ├── assets/                   # Custom CSS, JS, images
│   │   ├── css/styles.css
│   │   ├── js/app.js
│   │   └── images/external/
│   ├── chatbot.js               # AI chatbot widget
│   ├── chatbot.css              # Chatbot widget styles
│   └── chatbot-demo.html        # Chatbot demo page
│
├── resources/
│   ├── css/app.css
│   ├── js/app.js / bootstrap.js
│   └── views/                   # Blade templates
│       ├── layouts/app.blade.php
│       ├── components/           # Reusable Blade components
│       ├── auth/                 # Breeze auth views
│       ├── admin/dashboard.blade.php
│       ├── home.blade.php
│       ├── shop.blade.php
│       ├── product-detail.blade.php
│       ├── cart.blade.php
│       ├── shipping.blade.php
│       ├── payment.blade.php
│       ├── profile.blade.php
│       └── notifications.blade.php
│
├── routes/
│   ├── web.php                   # Web routes
│   ├── auth.php                  # Authentication routes
│   └── console.php             # Artisan commands
│
├── storage/                      # Laravel storage (logs, cache, uploads)
└── tests/                        # PHPUnit Feature & Unit tests
```

---

## Installation

### Prerequisites
- PHP >= 8.2
- Composer
- Node.js >= 18
- MySQL or SQLite
- Apache with `mod_rewrite` enabled (or Nginx configured for Laravel)

### Quick Setup (Recommended)

Run the Composer setup script to install everything at once:

```bash
composer run setup
```

This will:
1. Install PHP dependencies (`composer install`)
2. Copy `.env.example` to `.env`
3. Generate Laravel application key
4. Run database migrations
5. Install Node.js dependencies (`npm install --ignore-scripts`)
6. Build frontend assets (`npm run build`)

### Manual Setup

If you prefer step-by-step:

```bash
# 1. Install PHP dependencies
composer install

# 2. Create environment file
cp .env.example .env

# 3. Generate application key
php artisan key:generate

# 4. Configure database in .env (see Configuration section)

# 5. Run migrations
php artisan migrate

# 6. Seed database (optional - creates sample users and products)
php artisan db:seed

# 7. Install Node.js dependencies
npm install

# 8. Build frontend assets
npm run build
```

### Web Server Configuration

The project includes `.htaccess` files for Apache:
- Root `.htaccess` redirects all traffic to `public/`
- `public/.htaccess` handles Laravel URL rewriting to `index.php`

**Important:** Point your web server document root to the `public/` directory.

---

## Configuration

### Environment Variables

Key variables in `.env`:

```env
# Application
APP_NAME="The DS"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8080/theDS-Site/public

# Database (MySQL Example)
DB_CONNECTION=mysql
DB_HOST=
DB_PORT=port_number
DB_DATABASE=database_name
DB_USERNAME=
DB_PASSWORD=

# Database (SQLite Example)
DB_CONNECTION=sqlite
# Laravel will use database/database.sqlite

# Session & Cache
SESSION_DRIVER=file
CACHE_STORE=database
QUEUE_CONNECTION=database

# AI Chatbot (Groq)
GROQ_API_KEY=gsk_your_key_here
CHATBOT_PORT=3001

# Telegram Notifications
TELEGRAM_BOT_TOKEN=your_bot_token_here
TELEGRAM_CHAT_ID=your_chat_id_here

# Bakong KHQR Payment
BAKONG_ACCOUNT_ID=your_bakong_account
BAKONG_MERCHANT_NAME="the DS"
BAKONG_MERCHANT_CITY=PHNOM_PENH
```

### Getting API Keys

#### Groq API Key (AI Chatbot)
1. Visit [console.groq.com](https://console.groq.com)
2. Sign up with email or Google account
3. Go to **API Keys** → **Create API Key**
4. Copy your key (starts with `gsk_`)

#### Telegram Bot Token
1. Message [@BotFather](https://t.me/botfather) on Telegram
2. Create a new bot with `/newbot`
3. Copy the provided bot token
4. Get your chat ID by messaging the bot, then visiting:
   `https://api.telegram.org/bot<YOUR_TOKEN>/getUpdates`

#### Bakong KHQR
Contact Bakong or your bank to obtain a merchant account ID for KHQR payments.

---

## Database

### Supported Drivers
- **MySQL** — Recommended for production
- **SQLite** — Convenient for local development
- **SQL Server** — Enterprise deployments (raw SQL scripts provided)

### Migrations
Run all migrations:
```bash
php artisan migrate
```

### Raw SQL Scripts
Pre-built SQL scripts are available in `database/sql/` for setting up the schema and sample data without running Laravel migrations:

| File | Purpose |
|------|---------|
| `01_create_tables.sql` | `CREATE TABLE` statements for all 10 tables (SQL Server syntax) |
| `02_insert_data.sql` | Sample seed data (users, categories, products, promotions) |

Run them in order via SQL Server Management Studio (SSMS) or `sqlcmd`:
```bash
sqlcmd -S localhost -d your_database -i database/sql/01_create_tables.sql
sqlcmd -S localhost -d your_database -i database/sql/02_insert_data.sql
```

### Schema Overview

| Table | Purpose |
|-------|---------|
| `users` | Customers & admins (includes `is_admin` flag) |
| `password_reset_tokens` | Password reset tokens |
| `products` | Store inventory (name, slug, brand, price, image, gallery, stock, etc.) |
| `categories` | Product categories |
| `favorites` | User-product many-to-many relationship |
| `orders` | Customer orders with shipping details |
| `order_items` | Line items for each order |
| `promotions` | Coupon/promo codes |
| `notifications` | In-app notifications with `read_at` timestamps |

Laravel also creates internal framework tables (`cache`, `cache_locks`, `jobs`, `job_batches`, `failed_jobs`, `migrations`) automatically when migrations and queues are enabled.

### Seeded Data
The `DatabaseSeeder` creates:
- **3 Users:**
  - `test@example.com` / `password` (regular customer)
  - `sombath@gmail.com` / `password` (regular customer)
  - `admin@gmail.com` / `password` (administrator with `is_admin = true`)
- **8 Sample Products** — perfumes, sneakers, bags, and polo shirts
- **3 Promotions** — `WELCOME10`, `SAVE20`, `FLASH50`

---

## Routes & API Endpoints

### Public Store Routes

| Method | Route | Description |
|--------|-------|-------------|
| GET | `/` | Homepage — featured products & categories |
| GET | `/shop` | Product catalog with filtering |
| GET | `/product/{slug}` | Product detail page |
| GET | `/cart` | Shopping cart |
| POST | `/cart/add` | Add item to cart |
| POST | `/cart/update` | Update cart item quantity |
| POST | `/cart/remove` | Remove item from cart |
| GET | `/about` | About page |
| GET | `/terms` | Terms & conditions |
| GET | `/help-center` | Contact / help center |
| POST | `/help-center` | Submit help request |
| POST | `/chat-api` | AI chatbot proxy endpoint |

### Authenticated Routes (requires login)

| Method | Route | Description |
|--------|-------|-------------|
| GET | `/shipping` | Shipping information form |
| POST | `/shipping` | Save shipping details |
| GET | `/payment` | Payment page (shows KHQR) |
| POST | `/payment` | Process payment |
| POST | `/apply-promo` | Apply promotion code |
| POST | `/remove-promo` | Remove applied promotion |
| POST | `/favorites/toggle` | Toggle product favorite |
| GET | `/profile` | User profile |
| GET | `/profile/edit` | Edit profile form |
| PATCH | `/profile` | Update profile |
| DELETE | `/profile` | Delete account |
| GET | `/notifications` | Notification inbox |
| POST | `/notifications/{id}/read` | Mark notification as read |
| POST | `/notifications/read-all` | Mark all as read |

### Admin Routes (requires `auth` + `admin` middleware)

| Method | Route | Description |
|--------|-------|-------------|
| GET/POST | `/admin/login` | Admin login |
| POST | `/admin/logout` | Admin logout |
| GET/POST | `/admin/dashboard` | Admin dashboard |
| GET | `/admin/dashboard/orders-data` | AJAX orders data for charts |

### Authentication Routes
Standard Laravel Breeze routes handled in `routes/auth.php`:
- Login, Register, Logout
- Forgot / Reset Password
- Email Verification
- Password Confirmation

### Chatbot API (Node.js Server)

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `http://localhost:3001/api/chat` | Chat with AI assistant |

**Request body:**
```json
{
  "messages": [
    { "role": "user", "content": "Do you carry Nike sneakers?" }
  ]
}
```

**Response:**
```json
{
  "reply": "Yes, we carry a full range of Nike sneakers..."
}
```

---

## AI Chatbot (DS Assistant)

The DS Assistant is an embeddable AI customer support widget powered by Groq API.

### Architecture
- **Standalone Server** (`server.js`): Express app running on port `3001` that proxies messages to Groq API.
- **Laravel Proxy** (`ChatController`): CSRF-protected PHP endpoint at `POST /chat-api` that also calls Groq via cURL, maintaining session-based conversation history.
- **Widget** (`public/chatbot.js` + `public/chatbot.css`): Vanilla JS chat bubble (bottom-right corner) with no external dependencies.

### Running the Chatbot

```bash
npm run chatbot
```

Server starts at `http://localhost:3001`.

- **Demo:** `http://localhost:3001/chatbot-demo.html`
- **API:** `POST http://localhost:3001/api/chat`

### Embedding on Any Page

```html
<link rel="stylesheet" href="http://localhost:3001/chatbot.css">
<script src="http://localhost:3001/chatbot.js"></script>
```

The chat bubble appears automatically at the bottom-right.

### Customization
- **Colors:** Edit CSS variables in `public/chatbot.css` (`--ds-accent`, `--ds-dark`)
- **System prompt:** Edit `SYSTEM_PROMPT` in `server.js`
- **Model:** Change the `model` parameter in `server.js`. Options include `llama-3.3-70b-versatile`, `llama-4-scout-17b-16e-instruct`, `mixtral-8x7b-32768`, `gemma2-9b-it`

For more details, see [`chatbot-README.md`](chatbot-README.md).

---

## Payment Integration (Bakong KHQR)

Bakong KHQR is Cambodia's national QR code standard for unified payments.

### How It Works
1. At checkout, if Bakong is selected, the system generates a KHQR string for the order total.
2. The QR code is rendered as an image using `api.qrserver.com`.
3. The customer scans the QR code with their Bakong-supported banking app.
4. If Bakong credentials are invalid, the system gracefully falls back to a plain text QR.

### Configuration
Set these in `.env`:
```env
BAKONG_ACCOUNT_ID=your_account_id
BAKONG_MERCHANT_NAME="the DS"
BAKONG_MERCHANT_CITY=PHNOM_PENH
```

### Library
Uses `khqr-gateway/bakong-khqr-php` via Composer.

---

## Telegram Notifications

When an order is successfully placed, a richly formatted HTML message is sent to a configured Telegram bot/channel.

### Message Contents
- Order ID
- Customer name, phone, and email
- Shipping address
- Ordered items (brand, name, quantity, size, price)
- Order total

### Configuration
```env
TELEGRAM_BOT_TOKEN=your_bot_token
TELEGRAM_CHAT_ID=your_chat_id
```

### Service Location
`app/Services/TelegramService.php`

---

## Admin Dashboard

Access the admin panel at `/admin/login`.

**Default admin credentials:**
- Email: `admin@gmail.com`
- Password: `password`

### Dashboard Features
- **Stats Cards** — Total users, orders, products, and revenue.
- **Revenue Charts** — Chart.js-powered visualizations of sales over time.
- **Order Management** — View all orders, update statuses (pending → processing → shipped → delivered).
- **Product Management** — Full CRUD with support for local image upload or external image URLs.
- **Category Management** — Create and manage product categories.
- **User Requests** — View and manage help center submissions.
- **Announcements** — Send system-wide in-app notifications to all users.
- **Promotions** — Create promo codes, view usage statistics.
- **Reports** — Filterable by date range (today, 7 days, 30 days, month, year, all time).
  - Top selling products
  - Daily revenue breakdown
  - User growth
  - Order status distribution

---

## Development

### Running Locally

The easiest way to start all services:

```bash
composer run dev
```

This runs concurrently:
- `php artisan serve` — Laravel development server
- `php artisan queue:listen` — Queue worker
- `php artisan pail` — Real-time log monitoring
- `npm run dev` — Vite development server with HMR

### Available Scripts

**Composer:**
| Command | Description |
|---------|-------------|
| `composer run setup` | Full project setup |
| `composer run dev` | Start all development services |
| `composer run test` | Run PHPUnit tests |

**NPM:**
| Command | Description |
|---------|-------------|
| `npm run dev` | Start Vite dev server |
| `npm run build` | Build assets for production |
| `npm run chatbot` | Start AI chatbot server |

---

## Testing

Run the test suite:

```bash
composer run test
```

Or manually:
```bash
php artisan config:clear
php artisan test
```

Tests cover authentication, profile management, and core application features using PHPUnit.

---

## Security

### Implemented Protections
- **Security Headers Middleware** (`app/Http/Middleware/SecurityHeadersMiddleware.php`):
  - `X-Frame-Options: DENY`
  - `X-Content-Type-Options: nosniff`
  - `Referrer-Policy: strict-origin-when-cross-origin`
  - `Content-Security-Policy` (restrictive, allows self, Google Fonts, CDNs)
  - `X-XSS-Protection: 1; mode=block`
  - `Permissions-Policy`
- **CSRF Protection** — On all forms and the chatbot proxy endpoint.
- **Admin Middleware** — `EnsureIsAdmin` restricts admin routes to users with `is_admin = true`.
- **Password Hashing** — Laravel Bcrypt (12 rounds).

---

## Legacy Version

A complete earlier version of the site is preserved in `/legacy/`. It was built with plain PHP and includes:
- Its own SQLite database (`legacy/database/database.sqlite`)
- A simple admin panel
- Basic product catalog and cart functionality

This serves as a historical reference and fallback.

---

## License

This project is private and proprietary.
