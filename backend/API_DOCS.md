# Product Store API Documentation

Base URL: `http://localhost:8000/api`

## Authentication

The API uses Laravel Sanctum for authentication. After login, include the token in the Authorization header:

```
Authorization: Bearer {your-token}
```

## Endpoints

### Authentication

#### Register
```
POST /register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}

Response: 201 Created
{
  "message": "User registered successfully",
  "user": {...},
  "access_token": "token...",
  "token_type": "Bearer"
}
```

#### Login
```
POST /login
Content-Type: application/json

{
  "email": "john@example.com",
  "password": "password123"
}

Response: 200 OK
{
  "message": "Login successful",
  "user": {...},
  "access_token": "token...",
  "token_type": "Bearer"
}
```

#### Logout
```
POST /logout
Authorization: Bearer {token}

Response: 200 OK
{
  "message": "Logged out successfully"
}
```

#### Get User
```
GET /user
Authorization: Bearer {token}

Response: 200 OK
{
  "id": 1,
  "name": "John Doe",
  "email": "john@example.com",
  "role": "customer"
}
```

### Products

#### List Products
```
GET /products?category_id=1&search=phone&min_price=100&max_price=500&in_stock=1&sort_by=price&sort_order=asc&per_page=15

Response: 200 OK
{
  "data": [...],
  "links": {...},
  "meta": {...}
}
```

#### Get Product
```
GET /products/{id}

Response: 200 OK
{
  "id": 1,
  "name": "Product Name",
  "description": "...",
  "price": 99.99,
  "stock": 50,
  "category": {...}
}
```

#### Create Product (Admin only)
```
POST /products
Authorization: Bearer {admin-token}
Content-Type: application/json

{
  "name": "New Product",
  "description": "Description",
  "price": 99.99,
  "stock": 100,
  "category_id": 1,
  "image": "https://example.com/image.jpg"
}

Response: 201 Created
```

#### Update Product (Admin only)
```
PUT /products/{id}
Authorization: Bearer {admin-token}
Content-Type: application/json

{
  "name": "Updated Name",
  "price": 89.99
}

Response: 200 OK
```

#### Delete Product (Admin only)
```
DELETE /products/{id}
Authorization: Bearer {admin-token}

Response: 200 OK
{
  "message": "Product deleted successfully"
}
```

### Categories

#### List Categories
```
GET /categories

Response: 200 OK
[
  {
    "id": 1,
    "name": "Electronics",
    "description": "...",
    "products_count": 10
  }
]
```

#### Get Category
```
GET /categories/{id}

Response: 200 OK
```

#### Create Category (Admin only)
```
POST /categories
Authorization: Bearer {admin-token}
Content-Type: application/json

{
  "name": "New Category",
  "description": "Description",
  "image": "https://example.com/image.jpg"
}

Response: 201 Created
```

### Cart

#### Get Cart
```
GET /cart
Authorization: Bearer {token}

Response: 200 OK
{
  "cart": {
    "id": 1,
    "items": [...]
  },
  "total": 299.99
}
```

#### Add Item to Cart
```
POST /cart/items
Authorization: Bearer {token}
Content-Type: application/json

{
  "product_id": 1,
  "quantity": 2
}

Response: 200 OK
{
  "message": "Item added to cart",
  "cart": {...}
}
```

#### Update Cart Item
```
PUT /cart/items/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "quantity": 3
}

Response: 200 OK
```

#### Remove Cart Item
```
DELETE /cart/items/{id}
Authorization: Bearer {token}

Response: 200 OK
{
  "message": "Item removed from cart",
  "cart": {...}
}
```

#### Clear Cart
```
DELETE /cart
Authorization: Bearer {token}

Response: 200 OK
{
  "message": "Cart cleared"
}
```

### Orders

#### List Orders
```
GET /orders?status=pending
Authorization: Bearer {token}

Response: 200 OK
{
  "data": [...],
  "links": {...},
  "meta": {...}
}
```

#### Get Order
```
GET /orders/{id}
Authorization: Bearer {token}

Response: 200 OK
{
  "id": 1,
  "status": "pending",
  "total": 299.99,
  "items": [...],
  "promo_code": {...}
}
```

#### Create Order
```
POST /orders
Authorization: Bearer {token}
Content-Type: application/json

{
  "promo_code": "WELCOME10"  // optional
}

Response: 201 Created
{
  "message": "Order created successfully",
  "order": {...}
}
```

#### Update Order Status (Admin/Picker)
```
PUT /orders/{id}/status
Authorization: Bearer {admin-or-picker-token}
Content-Type: application/json

{
  "status": "processing"  // pending, processing, completed, cancelled
}

Response: 200 OK
```

### Promo Codes

#### Validate Promo Code
```
POST /promo-codes/validate
Authorization: Bearer {token}
Content-Type: application/json

{
  "code": "WELCOME10"
}

Response: 200 OK
{
  "valid": true,
  "promo_code": {...},
  "message": "Promo code is valid"
}
```

#### List Promo Codes (Admin only)
```
GET /promo-codes
Authorization: Bearer {admin-token}

Response: 200 OK
[...]
```

#### Create Promo Code (Admin only)
```
POST /promo-codes
Authorization: Bearer {admin-token}
Content-Type: application/json

{
  "code": "SUMMER20",  // optional, auto-generated if not provided
  "discount_percent": 20,
  "valid_from": "2024-01-01 00:00:00",
  "valid_until": "2024-12-31 23:59:59",
  "usage_limit": 100  // optional
}

Response: 201 Created
```

### Admin

#### Dashboard Statistics
```
GET /admin/dashboard
Authorization: Bearer {admin-token}

Response: 200 OK
{
  "stats": {
    "total_users": 100,
    "total_products": 50,
    "total_orders": 200,
    "total_revenue": 10000.00,
    ...
  },
  "recent_orders": [...],
  "top_products": [...],
  "revenue_by_month": [...]
}
```

#### List Users
```
GET /admin/users?role=customer
Authorization: Bearer {admin-token}

Response: 200 OK
```

#### Update User Role
```
PUT /admin/users/{id}/role
Authorization: Bearer {admin-token}
Content-Type: application/json

{
  "role": "admin"  // customer, admin, picker
}

Response: 200 OK
```

#### List All Orders
```
GET /admin/orders?status=pending&user_id=1
Authorization: Bearer {admin-token}

Response: 200 OK
```

## Error Responses

### Validation Error (422)
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": ["The email field is required."]
  }
}
```

### Unauthorized (401)
```json
{
  "message": "Unauthenticated."
}
```

### Forbidden (403)
```json
{
  "message": "Unauthorized. Insufficient permissions."
}
```

### Not Found (404)
```json
{
  "message": "Not found."
}
```

### Server Error (500)
```json
{
  "message": "Internal server error."
}
```

## Testing

Use the following credentials to test:

- **Admin**: admin@admin.com / password
- **Picker**: picker@picker.com / password
- **Customer**: customer@customer.com / password

Available promo codes:
- WELCOME10 (10% off)
- SUMMER20 (20% off)
- MEGA50 (50% off)
