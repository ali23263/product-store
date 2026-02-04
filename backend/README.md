# Product Store - Laravel Backend API

A complete Laravel backend API for an e-commerce product store application.

## Features

- **Authentication**: Laravel Sanctum with register, login, logout
- **User Roles**: Customer, Admin, Picker with role-based middleware
- **Products**: Full CRUD with filtering, search, and pagination
- **Categories**: Product categories management
- **Shopping Cart**: Add, update, remove items
- **Orders**: Create orders from cart, order management
- **Promo Codes**: Generate and validate discount codes
- **Admin Dashboard**: Statistics and analytics

## Tech Stack

- Laravel 10.x
- PostgreSQL
- Laravel Sanctum (API Authentication)
- Docker

## Database Schema

### Tables
- `users` - User accounts with roles
- `categories` - Product categories
- `products` - Product catalog
- `carts` - User shopping carts
- `cart_items` - Items in carts
- `orders` - Customer orders
- `order_items` - Items in orders
- `promo_codes` - Discount codes

## API Endpoints

### Public Routes
- `POST /api/register` - Register new user
- `POST /api/login` - Login
- `GET /api/products` - List products (with filters)
- `GET /api/products/{id}` - Get product details
- `GET /api/categories` - List categories
- `GET /api/categories/{id}` - Get category details

### Authenticated Routes
- `POST /api/logout` - Logout
- `GET /api/user` - Get authenticated user

#### Cart Management
- `GET /api/cart` - Get user's cart
- `POST /api/cart/items` - Add item to cart
- `PUT /api/cart/items/{id}` - Update cart item quantity
- `DELETE /api/cart/items/{id}` - Remove item from cart
- `DELETE /api/cart` - Clear cart

#### Orders
- `GET /api/orders` - Get user's orders
- `GET /api/orders/{id}` - Get order details
- `POST /api/orders` - Create order from cart
- `PUT /api/orders/{id}/status` - Update order status (admin/picker)

#### Promo Codes
- `POST /api/promo-codes/validate` - Validate promo code

### Admin Only Routes
- `POST /api/products` - Create product
- `PUT /api/products/{id}` - Update product
- `DELETE /api/products/{id}` - Delete product
- `POST /api/categories` - Create category
- `PUT /api/categories/{id}` - Update category
- `DELETE /api/categories/{id}` - Delete category
- `GET /api/promo-codes` - List promo codes
- `POST /api/promo-codes` - Create promo code
- `PUT /api/promo-codes/{id}` - Update promo code
- `DELETE /api/promo-codes/{id}` - Delete promo code
- `GET /api/admin/dashboard` - Dashboard statistics
- `GET /api/admin/users` - List users
- `PUT /api/admin/users/{id}/role` - Update user role
- `GET /api/admin/orders` - List all orders
- `PUT /api/admin/orders/{id}/status` - Update order status

## Installation

### Using Docker (Recommended)

1. The backend is already configured to work with Docker
2. Run migrations and seeders:
```bash
docker-compose exec backend php artisan migrate:fresh --seed
```

### Manual Installation

1. Install dependencies:
```bash
composer install
```

2. Copy environment file:
```bash
cp .env.example .env
```

3. Generate application key:
```bash
php artisan key:generate
```

4. Configure database in `.env`:
```
DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=product_store
DB_USERNAME=product_user
DB_PASSWORD=secret123
```

5. Run migrations and seeders:
```bash
php artisan migrate:fresh --seed
```

## Default Users

After seeding, the following users are available:

- **Admin**: admin@admin.com / password
- **Picker**: picker@picker.com / password
- **Customer**: customer@customer.com / password

## Default Promo Codes

- **WELCOME10** - 10% off (100 uses)
- **SUMMER20** - 20% off (50 uses)
- **MEGA50** - 50% off (10 uses, 7 days)

## Development

### Run migrations
```bash
php artisan migrate
```

### Seed database
```bash
php artisan db:seed
```

### Fresh migration with seed
```bash
php artisan migrate:fresh --seed
```

### Clear cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

## CORS Configuration

The API is configured to accept requests from `http://localhost:3000` (Vue.js frontend).
CORS settings can be modified in `config/cors.php`.

## Security

- Passwords are hashed using Laravel's built-in hashing
- API authentication via Laravel Sanctum
- CSRF protection enabled
- Role-based access control
- Input validation on all endpoints

## License

MIT License
