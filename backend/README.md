# E-Commerce Platform - Laravel Backend

A complete Laravel 10 backend for an e-commerce platform with role-based access control (Customer, Admin, Picker).

## Features

- **Authentication**: Laravel Sanctum for SPA
- **User Roles**: Customer, Admin, Picker
- **Product Management**: Categories, products with stock tracking
- **Shopping Cart**: Session-based for guests, persistent for authenticated users
- **Order Management**: Full order workflow with status tracking
- **Promo Codes**: Percentage and fixed discounts with expiration
- **Admin Dashboard**: Stats, user management, order management
- **Picker Interface**: Order picking and status updates

## Installation

1. Install dependencies:
```bash
composer install
```

2. Copy `.env.example` to `.env` and configure your database:
```bash
cp .env.example .env
```

3. Generate application key:
```bash
php artisan key:generate
```

4. Run migrations:
```bash
php artisan migrate
```

5. Seed the database:
```bash
php artisan db:seed
```

## Default Users

After seeding, you can login with:

- **Admin**: admin@example.com / password
- **Picker**: picker@example.com / password
- **Customer**: customer@example.com / password

## API Routes

### Public Routes
- `POST /api/register` - Register new user
- `POST /api/login` - Login
- `GET /api/categories` - List categories
- `GET /api/products` - List products (with filters)

### Authenticated Routes (Customer)
- `POST /api/logout` - Logout
- `GET /api/me` - Get current user
- `GET /api/cart` - Get cart
- `POST /api/cart/items` - Add item to cart
- `PUT /api/cart/items/{id}` - Update cart item
- `DELETE /api/cart/items/{id}` - Remove cart item
- `POST /api/orders` - Create order
- `GET /api/orders` - List user orders

### Admin Routes (prefix: /api/admin)
- `GET /dashboard/stats` - Dashboard statistics
- `GET /products` - List all products
- `POST /products` - Create product
- `PUT /products/{id}` - Update product
- `DELETE /products/{id}` - Delete product
- `GET /categories` - List categories
- `POST /categories` - Create category
- `GET /orders` - List all orders
- `PUT /orders/{id}/status` - Update order status
- `GET /promo-codes` - List promo codes
- `POST /promo-codes` - Create promo code
- `POST /promo-codes/generate` - Generate multiple promo codes
- `GET /users` - List users
- `PUT /users/{id}` - Update user

### Picker Routes (prefix: /api/picker)
- `GET /orders` - List pending/processing orders
- `GET /orders/{id}` - View order details
- `PUT /orders/{id}/status` - Update order status (processing, completed)

## Database Schema

- **users**: id, name, email, password, role
- **categories**: id, name, slug, description, image
- **products**: id, category_id, name, slug, description, price, stock, image, is_active
- **carts**: id, user_id, session_id
- **cart_items**: id, cart_id, product_id, quantity
- **orders**: id, user_id, status, total, promo_code_id, discount, shipping_address
- **order_items**: id, order_id, product_id, quantity, price
- **promo_codes**: id, code, discount_type, discount_value, min_purchase, max_uses, used_count, expires_at, is_active

## License

MIT
