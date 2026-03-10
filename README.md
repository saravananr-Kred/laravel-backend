# Library Management System POC

A production-ready Proof of Concept for a Library Management System built with PHP 8.2 and Laravel 11/12.

## Core Modules
- **Books**: Title, Author, ISBN, Category, Year, Availability.
- **Members**: Name, Email, Phone, Membership Date.
- **Borrowing**: Transaction records with automatic inventory management.

## Prerequisites
- PHP >= 8.2
- Composer
- MySQL
- Node.js & NPM

## Installation
1. Clone the repository.
2. Run `composer install`.
3. Run `npm install && npm run build`.
4. Configure `.env` with your DB credentials.
5. Run `php artisan migrate --seed`.

## Default Access
- **URL**: `http://localhost` (or your local dev server)
- **Email**: `admin@example.com`
- **Password**: `password`

## Design Principles
- **Clean Code**: SOLID principles and Laravel best practices.
- **Scalability**: Decoupled logic using Form Requests and Resource Controllers.
- **Extendable**: Ready for RBAC, API endpoints, and fine systems.
