# Laravel E-Commerce Backend - Setup Guide

## What's Included

This is a complete Laravel 10 e-commerce backend built from scratch with:

### 📁 Project Structure
- ✅ Laravel 10 framework with all dependencies
- ✅ Composer configuration (composer.json + composer.lock)
- ✅ Environment configuration (.env.example)
- ✅ Complete directory structure (app, config, database, routes, etc.)

### 🗄️ Database
- ✅ 9 migrations (users, categories, products, carts, cart_items, orders, order_items, promo_codes)
- ✅ Database seeder with sample data
- ✅ 3 test users (admin, picker, customer)
- ✅ 5 categories and 20 products
- ✅ 2 active promo codes

### 📦 Models & Relationships
- ✅ User (hasMany orders, hasOne cart)
- ✅ Category (hasMany products)
- ✅ Product (belongsTo category, hasMany cartItems/orderItems)
- ✅ Cart (belongsTo user, hasMany items)
- ✅ CartItem (belongsTo cart/product)
- ✅ Order (belongsTo user/promoCode, hasMany items)
- ✅ OrderItem (belongsTo order/product)
- ✅ PromoCode (hasMany orders)

### 🔐 Authentication & Authorization
- ✅ Laravel Sanctum for SPA authentication
- ✅ Role-based access control (customer, admin, picker)
- ✅ Custom CheckRole middleware
- ✅ CORS configured for localhost:3000

### 🛣️ API Routes

#### Public (No Auth)
- `POST /api/register` - Register
- `POST /api/login` - Login
- `GET /api/categories` - List categories
- `GET /api/categories/{id}` - Get category
- `GET /api/products` - List products (filters: category_id, search, min_price, max_price, sort_by, sort_order)
- `GET /api/products/{id}` - Get product

#### Customer (Auth Required)
- `POST /api/logout` - Logout
- `GET /api/me` - Current user
- `GET /api/cart` - Get cart
- `POST /api/cart/items` - Add to cart
- `PUT /api/cart/items/{id}` - Update cart item
- `DELETE /api/cart/items/{id}` - Remove from cart
- `DELETE /api/cart` - Clear cart
- `POST /api/orders` - Create order (with promo code support)
- `GET /api/orders` - User's orders
- `GET /api/orders/{id}` - Get order

#### Admin (/api/admin/*)
- `GET /dashboard/stats` - Dashboard stats
- `GET|POST|PUT|DELETE /categories` - Category management
- `GET|POST|PUT|DELETE /products` - Product management
- `GET /orders` - All orders
- `PUT /orders/{id}/status` - Update order status
- `GET|POST|PUT|DELETE /promo-codes` - Promo code management
- `POST /promo-codes/generate` - Generate bulk promo codes
- `GET|PUT /users` - User management

#### Picker (/api/picker/*)
- `GET /orders` - Pending/processing orders
- `GET /orders/{id}` - Order details
- `PUT /orders/{id}/status` - Update status (processing, completed)

## 🚀 Setup Instructions

### 1. Install Dependencies
```bash
cd backend
composer install
```

### 2. Configure Environment
```bash
cp .env.example .env
# Edit .env to set database credentials:
# DB_CONNECTION=pgsql
# DB_HOST=db
# DB_PORT=5432
# DB_DATABASE=ecommerce
# DB_USERNAME=postgres
# DB_PASSWORD=postgres
```

### 3. Generate Application Key
```bash
php artisan key:generate
```

### 4. Run Migrations
```bash
php artisan migrate
```

### 5. Seed Database
```bash
php artisan db:seed
```

## 📝 Test Users

After seeding, you can login with:

| Email | Password | Role |
|-------|----------|------|
| admin@example.com | password | admin |
| picker@example.com | password | picker |
| customer@example.com | password | customer |

## 🧪 Testing the API

### Login Example
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"customer@example.com","password":"password"}'
```

### Get Products
```bash
curl http://localhost:8000/api/products
```

### Add to Cart (Authenticated)
```bash
curl -X POST http://localhost:8000/api/cart/items \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"product_id":1,"quantity":2}'
```

### Create Order with Promo Code
```bash
curl -X POST http://localhost:8000/api/orders \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"shipping_address":"123 Main St","promo_code":"SAVE10"}'
```

## 🔧 Features Implemented

### Cart System
- ✅ Session-based cart for guests
- ✅ Persistent cart for authenticated users
- ✅ Stock validation on add
- ✅ Automatic total calculation

### Order System
- ✅ Create from cart with stock validation
- ✅ Promo code validation and application
- ✅ Status workflow: pending → processing → completed/cancelled
- ✅ Stock deduction on order creation
- ✅ Order history for customers

### Promo Code System
- ✅ Percentage and fixed discounts
- ✅ Minimum purchase requirement
- ✅ Maximum usage limit
- ✅ Expiration date
- ✅ Active/inactive toggle
- ✅ Bulk generation (admin)

### Product Management
- ✅ CRUD operations
- ✅ Category assignment
- ✅ Stock tracking
- ✅ Active/inactive status
- ✅ Search and filtering
- ✅ Price range filters
- ✅ Sorting

### Admin Dashboard
- ✅ Total sales
- ✅ Order count
- ✅ Customer count
- ✅ Pending orders
- ✅ Recent orders
- ✅ Top selling products

## 🔒 Security Features
- ✅ Password hashing
- ✅ Token-based authentication
- ✅ CSRF protection (web routes)
- ✅ Role-based authorization
- ✅ Input validation on all endpoints
- ✅ SQL injection prevention (Eloquent ORM)

## 📊 Database Schema

```
users (id, name, email, password, role, timestamps)
categories (id, name, slug, description, image, timestamps)
products (id, category_id, name, slug, description, price, stock, image, is_active, timestamps)
carts (id, user_id, session_id, timestamps)
cart_items (id, cart_id, product_id, quantity, timestamps)
orders (id, user_id, status, total, promo_code_id, discount, shipping_address, timestamps)
order_items (id, order_id, product_id, quantity, price, timestamps)
promo_codes (id, code, discount_type, discount_value, min_purchase, max_uses, used_count, expires_at, is_active, timestamps)
```

## 🎯 Next Steps

1. Set up Docker container (if using Docker)
2. Run migrations and seeders
3. Test API endpoints
4. Integrate with frontend (Vue.js)
5. Add file upload for product images
6. Implement payment gateway
7. Add email notifications

## 📚 Technology Stack

- **Framework**: Laravel 10.x
- **Authentication**: Laravel Sanctum
- **Database**: PostgreSQL 15
- **PHP**: 8.1+
- **Testing**: PHPUnit 10.x
