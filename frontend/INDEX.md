# 📑 Product Store Frontend - Feature Index

## 🎯 Navigation Map

### For Developers
- [README.md](./README.md) - Start here for full documentation
- [QUICKSTART.md](./QUICKSTART.md) - Get up and running quickly
- [PROJECT_OVERVIEW.md](./PROJECT_OVERVIEW.md) - Understand the architecture
- [DEPLOYMENT.md](./DEPLOYMENT.md) - Deploy to production
- [COMPLETION_SUMMARY.md](./COMPLETION_SUMMARY.md) - What's been built

---

## 📱 User Journeys

### Customer Journey
```
Landing (/) 
  → Browse Products
  → Search/Filter
  → View Product (/product/:id)
  → Add to Cart
  → View Cart (/cart)
  → Apply Promo Code
  → Login/Register (/login, /register)
  → Checkout
  → View Dashboard (/dashboard)
  → Track Orders
```

### Admin Journey
```
Login (/login)
  → Admin Panel (/admin)
  → Dashboard Tab (Stats)
  → Products Tab (CRUD)
  → Categories Tab (Manage)
  → Orders Tab (Update Status)
  → Promo Codes Tab (Generate)
  → Users Tab (View All)
```

### Picker Journey
```
Login (/login)
  → Picker Dashboard (/picker)
  → Filter by Status
  → View Orders
  → Update Status (Pending → Processing → Ready → Shipped → Delivered)
  → View Details
```

---

## 🗂️ File Organization

### Pages (`src/pages/`)
| File | Route | Purpose | Access |
|------|-------|---------|--------|
| Home.vue | `/` | Product catalog | Public |
| ProductDetail.vue | `/product/:id` | Product details | Public |
| Cart.vue | `/cart` | Shopping cart | Public |
| Login.vue | `/login` | User login | Guest |
| Register.vue | `/register` | User registration | Guest |
| Dashboard.vue | `/dashboard` | Customer dashboard | Customer |
| Admin.vue | `/admin` | Admin panel | Admin |
| Picker.vue | `/picker` | Order processing | Picker |

### Components (`src/components/`)

**Layout Components:**
- `Header.vue` - Site navigation with cart badge
- `Footer.vue` - Site footer with links
- `Modal.vue` - Reusable modal dialog
- `Toast.vue` - Toast notification system
- `LoadingSpinner.vue` - Loading indicator

**Display Components:**
- `ProductCard.vue` - Product display card
- `CartItem.vue` - Cart item with controls
- `OrderCard.vue` - Order display card

**Admin Components (`src/components/admin/`):**
- `DashboardStats.vue` - Statistics dashboard
- `ProductsManagement.vue` - Product CRUD table
- `CategoriesManagement.vue` - Category management
- `OrdersManagement.vue` - Order management table
- `PromoCodesManagement.vue` - Promo code generator
- `UsersManagement.vue` - User list table

### Stores (`src/stores/`)

| Store | State | Actions |
|-------|-------|---------|
| auth.js | user, token, isAuthenticated | login, register, logout |
| cart.js | items, itemCount, total | addItem, removeItem, updateQuantity |
| products.js | products, categories, filters | fetchProducts, createProduct, updateProduct |
| orders.js | orders, appliedPromo | fetchOrders, createOrder, applyPromoCode |

---

## 🎨 UI Component Library

### Buttons
```vue
<button class="btn-primary">Primary Action</button>
<button class="btn-secondary">Secondary Action</button>
```

### Forms
```vue
<input class="input-field" type="text" />
<select class="input-field">...</select>
<textarea class="input-field">...</textarea>
```

### Cards
```vue
<div class="card">
  <!-- Card content -->
</div>
```

### Status Badges
```javascript
pending: 'bg-yellow-100 text-yellow-800'
processing: 'bg-blue-100 text-blue-800'
ready: 'bg-purple-100 text-purple-800'
shipped: 'bg-indigo-100 text-indigo-800'
delivered: 'bg-green-100 text-green-800'
```

---

## 🔌 API Endpoints

### Authentication
- `POST /api/login` - User login
- `POST /api/register` - User registration
- `POST /api/logout` - User logout
- `GET /api/user` - Get current user

### Products
- `GET /api/products` - List products (with filters)
- `GET /api/products/:id` - Get product details
- `POST /api/products` - Create product (admin)
- `PUT /api/products/:id` - Update product (admin)
- `DELETE /api/products/:id` - Delete product (admin)

### Categories
- `GET /api/categories` - List categories
- `POST /api/categories` - Create category (admin)
- `DELETE /api/categories/:id` - Delete category (admin)

### Orders
- `GET /api/orders` - List orders
- `GET /api/orders/:id` - Get order details
- `POST /api/orders` - Create order
- `PATCH /api/orders/:id/status` - Update order status

### Promo Codes
- `GET /api/promo-codes` - List promo codes (admin)
- `POST /api/promo-codes` - Create promo code (admin)
- `POST /api/promo-codes/validate` - Validate promo code
- `DELETE /api/promo-codes/:id` - Delete promo code (admin)

### Users & Stats
- `GET /api/users` - List users (admin)
- `GET /api/stats/dashboard` - Get dashboard stats (admin)

---

## 🎨 Color Palette

### Primary Colors
```css
primary-50:  #f5f3ff
primary-100: #ede9fe
primary-200: #ddd6fe
primary-300: #c4b5fd
primary-400: #a78bfa
primary-500: #8b5cf6
primary-600: #7c3aed  /* Main primary color */
primary-700: #6d28d9
primary-800: #5b21b6
primary-900: #4c1d95
```

### Semantic Colors
```css
Success: #10b981 (Green-500)
Error:   #ef4444 (Red-500)
Warning: #f59e0b (Yellow-500)
Info:    #3b82f6 (Blue-500)
```

---

## 🔐 Access Control

### Public Routes
- `/` - Home
- `/product/:id` - Product details
- `/cart` - Shopping cart
- `/login` - Login page
- `/register` - Register page

### Protected Routes
- `/dashboard` - Requires: Customer role
- `/admin` - Requires: Admin role
- `/picker` - Requires: Picker role

---

## 📦 State Management

### Auth Store
```javascript
// State
user: Object | null
token: String | null
loading: Boolean

// Getters
isAuthenticated: Boolean
isAdmin: Boolean
isPicker: Boolean
isCustomer: Boolean

// Actions
login(credentials)
register(userData)
logout()
```

### Cart Store
```javascript
// State
items: Array

// Getters
itemCount: Number
subtotal: Number
total: Number

// Actions
addItem(product, quantity)
removeItem(productId)
updateQuantity(productId, quantity)
clearCart()
```

---

## 🚀 Development Workflow

### Start Development
```bash
npm run dev
```

### Build for Production
```bash
npm run build
```

### Preview Production Build
```bash
npm run preview
```

### Check Bundle Size
```bash
npm run build
# Check dist/ folder
```

---

## 🧪 Testing Checklist

### Authentication
- [ ] Login with valid credentials
- [ ] Login with invalid credentials
- [ ] Register new account
- [ ] Logout
- [ ] Token persistence
- [ ] Protected route access

### Shopping
- [ ] Browse products
- [ ] Search products
- [ ] Filter by category
- [ ] View product details
- [ ] Add to cart
- [ ] Update cart quantities
- [ ] Remove from cart
- [ ] Apply promo code
- [ ] Checkout

### Admin
- [ ] View dashboard stats
- [ ] Create product
- [ ] Update product
- [ ] Delete product
- [ ] Manage categories
- [ ] Generate promo code
- [ ] Update order status
- [ ] View users

### Responsive
- [ ] Mobile (< 640px)
- [ ] Tablet (640px - 1024px)
- [ ] Desktop (> 1024px)

---

## 📞 Support & Resources

### Documentation
- Vue.js 3: https://vuejs.org/
- Tailwind CSS: https://tailwindcss.com/
- Pinia: https://pinia.vuejs.org/
- Vue Router: https://router.vuejs.org/

### Project Files
- Issues & Questions: See README.md
- Quick Start: See QUICKSTART.md
- Deployment: See DEPLOYMENT.md

---

## 🎯 Quick Links

| What | Where |
|------|-------|
| Full Documentation | [README.md](./README.md) |
| Getting Started | [QUICKSTART.md](./QUICKSTART.md) |
| Architecture | [PROJECT_OVERVIEW.md](./PROJECT_OVERVIEW.md) |
| Deploy Guide | [DEPLOYMENT.md](./DEPLOYMENT.md) |
| What's Done | [COMPLETION_SUMMARY.md](./COMPLETION_SUMMARY.md) |

---

**Last Updated**: February 4, 2024  
**Version**: 1.0.0  
**Status**: ✅ Production Ready
