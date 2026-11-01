# NOVARO — Men's Clothing E-commerce

**NOVARO — Defined by Style**

A full-stack men's clothing e-commerce platform built with Laravel and Vue.js, featuring a modern storefront, advanced product filtering, product details, and an administrative dashboard.

## Screenshots

### 1. Home Page
![NOVARO Home Page](screenshots/home-page.png)

### 2. Product Filtering
![Product Filtering](screenshots/product-filters.png)

### 3. Product Details
![Product Details](screenshots/product-details.png)

### 4. Admin Dashboard
![Admin Dashboard](screenshots/admin-dashboard.png)

## Features

### Storefront
- Responsive home page and product catalog
- Product details and gallery
- Product filtering and search
- Product attributes and variations
- Shopping cart and checkout
- Discount codes
- User accounts and profile management
- Product comments and ratings
- Order history and tracking

### Administration
- Admin dashboard
- Product and category management
- Product attributes and values
- Orders and customers management
- User roles and permissions
- Discount and badge management
- Website settings and configuration

### Authentication & Security
- User authentication and registration
- Social login integrations
- Two-factor authentication support
- Role-based access control
- Form validation and security protections

## Technology Stack

| Area | Technologies |
|---|---|
| Backend | PHP, Laravel |
| Frontend | Vue.js, JavaScript |
| UI | Blade, Bootstrap, Tailwind CSS |
| Build Tool | Vite |
| Database | MySQL |
| State Management | Pinia |
| Routing | Vue Router |
| Internationalization | Vue I18n |
| Version Control | Git |

## Requirements

- PHP 8.2 or higher
- Composer
- Node.js and npm
- MySQL or another Laravel-supported database

## Installation

Clone the repository:

```bash
git clone https://github.com/matweb80/novaro-men-clothing-project.git
cd novaro-men-clothing-project
```

Install PHP dependencies:

```bash
composer install
```

Install frontend dependencies:

```bash
npm install
```

Create the environment file:

```bash
cp .env.example .env
```

Generate the application key:

```bash
php artisan key:generate
```

Configure your database and required environment variables in `.env`.

Run database migrations:

```bash
php artisan migrate
```

Build frontend assets:

```bash
npm run build
```

Start the Laravel development server:

```bash
php artisan serve
```

For frontend development with Vite, run:

```bash
npm run dev
```

## Environment Configuration

Configure the required settings in your `.env` file, including:

- Application URL and environment
- Database connection
- Mail settings
- OAuth credentials (if social login is enabled)
- Payment gateway credentials
- Google reCAPTCHA credentials (if enabled)

**Never commit your actual `.env` file or private API credentials to the repository.**

## Project Status

This project is an ongoing personal portfolio project. Features and implementation may evolve over time.

## License

No license has been specified yet.