# Quick Start Guide - Laravel Backend

## Prerequisites
- Docker and Docker Compose installed
- OR PHP 8.1+, Composer, and PostgreSQL

## Method 1: Using Docker (Recommended)

### 1. Start the services
```bash
cd /path/to/product-store
docker-compose up -d
```

### 2. Install dependencies and setup
```bash
# Enter the backend container
docker-compose exec backend bash

# Install dependencies
composer install

# Generate application key
php artisan key:generate

# Run migrations and seeders
php artisan migrate:fresh --seed

# Exit container
exit
```

### 3. Access the API
- API Base URL: http://localhost:8000/api
- Health check: http://localhost:8000/

### 4. Test the API
```bash
# Register a new user
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'

# Login
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@admin.com",
    "password": "password"
  }'

# List products (no auth required)
curl http://localhost:8000/api/products
```

## Method 2: Local Development (Without Docker)

### 1. Install dependencies
```bash
cd backend
composer install
```

### 2. Configure environment
```bash
cp .env.example .env
# Edit .env and configure your PostgreSQL database
php artisan key:generate
```

### 3. Setup database
```bash
# Make sure PostgreSQL is running
php artisan migrate:fresh --seed
```

### 4. Start the development server
```bash
php artisan serve --host=0.0.0.0 --port=8000
```

## Default Credentials

After seeding, use these credentials:

### Admin User
- Email: `admin@admin.com`
- Password: `password`
- Can: Manage products, categories, promo codes, users, view dashboard

### Picker User
- Email: `picker@picker.com`
- Password: `password`
- Can: Update order status to processing/completed

### Customer User
- Email: `customer@customer.com`
- Password: `password`
- Can: Browse products, manage cart, create orders

## Available Promo Codes

- `WELCOME10` - 10% discount (100 uses available)
- `SUMMER20` - 20% discount (50 uses available)
- `MEGA50` - 50% discount (10 uses available, expires in 7 days)

## Common Tasks

### Reset database
```bash
php artisan migrate:fresh --seed
```

### Clear cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

### Run tests
```bash
php artisan test
```

### Using Makefile
```bash
make fresh      # Fresh migration with seed
make migrate    # Run migrations
make seed       # Run seeders
make test       # Run tests
make clear      # Clear all caches
make optimize   # Optimize for production
```

## API Testing Examples

### 1. Authentication Flow
```bash
# Register
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{"name":"John Doe","email":"john@example.com","password":"password123","password_confirmation":"password123"}'

# Login (save the token)
TOKEN=$(curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@admin.com","password":"password"}' \
  | jq -r '.access_token')

# Get authenticated user
curl http://localhost:8000/api/user \
  -H "Authorization: Bearer $TOKEN"
```

### 2. Browse Products
```bash
# List all products
curl http://localhost:8000/api/products

# Filter by category
curl "http://localhost:8000/api/products?category_id=1"

# Search products
curl "http://localhost:8000/api/products?search=headphone"

# Filter by price range
curl "http://localhost:8000/api/products?min_price=50&max_price=200"
```

### 3. Shopping Cart
```bash
# Get cart
curl http://localhost:8000/api/cart \
  -H "Authorization: Bearer $TOKEN"

# Add item to cart
curl -X POST http://localhost:8000/api/cart/items \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"product_id":1,"quantity":2}'

# Update cart item
curl -X PUT http://localhost:8000/api/cart/items/1 \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"quantity":3}'
```

### 4. Create Order
```bash
# Create order from cart (with promo code)
curl -X POST http://localhost:8000/api/orders \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"promo_code":"WELCOME10"}'

# Get orders
curl http://localhost:8000/api/orders \
  -H "Authorization: Bearer $TOKEN"
```

### 5. Admin Operations
```bash
# Login as admin
ADMIN_TOKEN=$(curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@admin.com","password":"password"}' \
  | jq -r '.access_token')

# Get dashboard stats
curl http://localhost:8000/api/admin/dashboard \
  -H "Authorization: Bearer $ADMIN_TOKEN"

# Create product
curl -X POST http://localhost:8000/api/products \
  -H "Authorization: Bearer $ADMIN_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "name":"New Product",
    "description":"Product description",
    "price":99.99,
    "stock":100,
    "category_id":1
  }'

# Create promo code
curl -X POST http://localhost:8000/api/promo-codes \
  -H "Authorization: Bearer $ADMIN_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "discount_percent":15,
    "valid_from":"2024-01-01 00:00:00",
    "valid_until":"2024-12-31 23:59:59",
    "usage_limit":50
  }'
```

## Troubleshooting

### Database connection failed
1. Check if PostgreSQL is running
2. Verify credentials in `.env` file
3. Make sure database exists: `product_store`

### Permission denied on storage/logs
```bash
chmod -R 775 storage bootstrap/cache
```

### Composer dependencies missing
```bash
composer install
```

### App key not set
```bash
php artisan key:generate
```

### CORS errors from frontend
1. Check `config/cors.php`
2. Verify `FRONTEND_URL` in `.env`
3. Clear config cache: `php artisan config:clear`

## Development Workflow

### 1. Making database changes
```bash
# Create new migration
php artisan make:migration create_something_table

# Edit migration file
# database/migrations/YYYY_MM_DD_HHMMSS_create_something_table.php

# Run migration
php artisan migrate

# Rollback if needed
php artisan migrate:rollback
```

### 2. Creating new endpoints
```bash
# Create controller
php artisan make:controller Api/SomethingController

# Add routes in routes/api.php
# Add logic in controller
```

### 3. Testing changes
```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter ExampleTest
```

## Production Deployment Checklist

- [ ] Set `APP_ENV=production` in `.env`
- [ ] Set `APP_DEBUG=false` in `.env`
- [ ] Generate new `APP_KEY`
- [ ] Configure production database credentials
- [ ] Set correct `FRONTEND_URL`
- [ ] Run `composer install --optimize-autoloader --no-dev`
- [ ] Run `php artisan config:cache`
- [ ] Run `php artisan route:cache`
- [ ] Run `php artisan view:cache`
- [ ] Set up proper file permissions
- [ ] Enable HTTPS
- [ ] Configure rate limiting
- [ ] Set up monitoring and logging
- [ ] Configure backup strategy

## Useful Commands

```bash
# Laravel commands
php artisan migrate          # Run migrations
php artisan db:seed         # Run seeders
php artisan migrate:fresh   # Fresh migration
php artisan tinker          # Laravel REPL
php artisan route:list      # List all routes
php artisan queue:work      # Process queue jobs

# Composer commands
composer install            # Install dependencies
composer update            # Update dependencies
composer dump-autoload    # Regenerate autoload

# Docker commands
docker-compose up -d           # Start services
docker-compose down           # Stop services
docker-compose exec backend bash  # Enter backend container
docker-compose logs -f backend    # View backend logs
```

## Support & Documentation

- Laravel Documentation: https://laravel.com/docs
- Laravel Sanctum: https://laravel.com/docs/sanctum
- API Documentation: See `API_DOCS.md`
- Implementation Details: See `IMPLEMENTATION_SUMMARY.md`

## Need Help?

If you encounter issues:
1. Check the logs: `storage/logs/laravel.log`
2. Review `.env` configuration
3. Verify database connection
4. Check Docker logs: `docker-compose logs backend`
5. Ensure all migrations ran: `php artisan migrate:status`
