# Assam Nursing Council - Backend API

This is the backend API for the Assam Nurses' Midwives' & Health Visitors' Council portal, built with **Laravel 11**. It serves as the central data source for both the public-facing website and the administrative dashboard.

## Tech Stack
- **Framework**: Laravel 11 (PHP 8.2+)
- **Database**: MySQL
- **Authentication**: Laravel Sanctum (Token-based)
- **Media Management**: Spatie MediaLibrary
- **Storage**: Local Storage (configured for Hostinger shared hosting)

## Prerequisites
- PHP >= 8.2
- Composer
- MySQL

## Installation & Setup

1. **Clone the repository and install dependencies**
   ```bash
   composer install
   ```

2. **Environment Configuration**
   Copy the example environment file and generate an application key:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Configure Database**
   Update your `.env` file with your local MySQL credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

4. **Run Migrations and Seeders**
   ```bash
   php artisan migrate:fresh --seed
   ```
   *(This will create a default super-admin account).*

5. **Storage Link**
   Ensure the storage directory is accessible to the public:
   ```bash
   php artisan storage:link
   ```

6. **Serve the Application**
   ```bash
   php artisan serve
   ```
   The API will be available at `http://localhost:8000/api`.

## Key Features
- Dynamic CMS configuration endpoints for the frontend.
- Media management utilizing Spatie MediaLibrary for handling circulars, forms, and institute data.
- Robust role-based API protection via Sanctum middleware.
