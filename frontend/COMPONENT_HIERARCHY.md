# Component Hierarchy & Data Flow

## Application Structure

```
App.vue (Root)
│
└── RouterView
    │
    └── MainLayout
        ├── Navbar (cart badge from cartStore)
        ├── RouterView (page content)
        └── Footer
```

## Page Component Trees

### 🏠 HomePage
```
HomePage
├── Hero Section (static)
├── Categories Grid
│   └── RouterLink × N (categories)
├── Featured Products
│   └── ProductCard × 8
│       └── uses cartStore.addItem()
└── Features Section (static)
```

### 📦 ProductsPage
```
ProductsPage
├── ProductFilters (sidebar)
│   ├── Search input → productsStore.setFilter()
│   ├── Category select → productsStore.setFilter()
│   ├── Price range → productsStore.setFilter()
│   └── Sort select → productsStore.setFilter()
└── ProductCard × N (filtered products)
    └── uses cartStore.addItem()
```

### 🔍 ProductDetailPage
```
ProductDetailPage
├── Product Image
├── Product Info
│   ├── Name, Price, Description
│   ├── Stock Indicator
│   └── Quantity Selector
├── Add to Cart Button
│   └── calls cartStore.addItem()
└── AlertMessage (success feedback)
```

### 🛒 CartPage
```
CartPage
├── CartItem × N
│   ├── Quantity Controls
│   │   └── calls cartStore.updateQuantity()
│   └── Remove Button
│       └── calls cartStore.removeItem()
└── CartSummary
    ├── Promo Code Input
    │   └── calls cartStore.applyPromoCode()
    └── Proceed to Checkout Button
```

### 💳 CheckoutPage
```
CheckoutPage
├── Shipping Form
│   ├── Address Fields
│   └── Submit → ordersStore.createOrder()
├── CartSummary (order summary)
│   ├── Promo Code
│   └── Totals
└── Order Items List (mini)
```

### 👤 DashboardPage
```
DashboardPage
├── Tab Navigation (Orders | Profile)
├── Orders Tab
│   └── OrderCard × N
│       └── RouterLink to order detail
└── Profile Tab
    └── User Info Display (from authStore.user)
```

### 📋 OrderDetailPage
```
OrderDetailPage
├── Order Header
│   ├── Order ID
│   ├── Status Badge
│   └── Total
├── Shipping Info
├── Order Items List
    └── Item × N
        ├── Product Image
        ├── Product Name
        ├── Quantity
        └── Price
```

## Admin Component Trees

### 📊 Admin DashboardPage
```
Admin DashboardPage
├── Stats Cards × 4
│   ├── Total Sales
│   ├── Total Orders
│   ├── Total Customers
│   └── Pending Orders
├── Quick Links × 3
└── Recent Orders Table
    └── RouterLink to order detail
```

### 🏷️ Admin ProductsPage
```
Admin ProductsPage
├── Add Product Button
└── Products Table
    ├── Product Row × N
    │   ├── Image
    │   ├── Name, Category, Price, Stock
    │   ├── Edit Button → ProductFormPage
    │   └── Delete Button → productsStore.deleteProduct()
```

### ✏️ Admin ProductFormPage
```
Admin ProductFormPage
└── Product Form
    ├── Name Input
    ├── Description Textarea
    ├── Price Input
    ├── Stock Input
    ├── Category Select (from productsStore.categories)
    ├── Image URL Input
    └── Submit → productsStore.createProduct() or updateProduct()
```

### 🎫 Admin PromoCodesPage
```
Admin PromoCodesPage
├── Create Form (toggleable)
│   ├── Code Input (with generator)
│   ├── Discount Type Select
│   ├── Discount Value Input
│   ├── Min Purchase Input
│   ├── Max Uses Input
│   ├── Expiration Date Input
│   └── Submit → api.post('/admin/promo-codes')
└── Promo Codes Table
    └── Promo Row × N
        └── Delete Button
```

### 📦 Admin OrdersPage
```
Admin OrdersPage
├── Status Filter Select
└── Orders Table
    └── Order Row × N
        ├── Order ID, Customer, Total, Status
        └── View Button → OrderDetailPage
```

### 📝 Admin OrderDetailPage
```
Admin OrderDetailPage
├── Order Header (ID, Status, Total)
├── Status Update Form
│   ├── Status Select
│   └── Update Button → ordersStore.updateOrderStatus()
├── Customer Info
├── Shipping Info
└── Order Items List
```

## Picker Component Trees

### 📋 Picker OrdersPage
```
Picker OrdersPage
├── Status Filter Select
└── Orders Table
    └── Order Row × N
        └── Pick Order Button → OrderDetailPage
```

### ✅ Picker OrderDetailPage
```
Picker OrderDetailPage
├── Order Header + Progress Bar
│   └── Shows picked/total items
├── Shipping Info
├── Items to Pick List
│   └── Item × N (clickable checkboxes)
│       ├── Checkbox (toggle picked state)
│       ├── Product Image
│       ├── Product Name
│       ├── Quantity Badge
│       └── Picked Status Badge
└── Action Buttons
    ├── Start Picking → status: processing
    └── Mark as Shipped → status: shipped
        └── requires all items picked
```

## Data Flow Patterns

### Authentication Flow
```
LoginPage
    └── form submit
        └── authStore.login(credentials)
            ├── api.post('/login')
            ├── save token to localStorage
            ├── save user to localStorage
            └── cartStore.syncCart()
                └── api.post('/cart/sync')
```

### Cart Flow (Guest)
```
ProductCard
    └── addToCart()
        └── cartStore.addItem(product)
            ├── update items array
            └── localStorage.setItem('cart', items)
```

### Cart Flow (Authenticated)
```
ProductCard
    └── addToCart()
        └── cartStore.addItem(product)
            ├── update items array
            ├── localStorage.setItem('cart', items)
            └── api.post('/cart/items', item)
```

### Checkout Flow
```
CheckoutPage
    └── form submit
        └── ordersStore.createOrder(orderData)
            ├── api.post('/orders', data)
            ├── cartStore.clearCart()
            │   ├── clear items array
            │   └── localStorage.removeItem('cart')
            └── router.push(`/orders/${order.id}`)
```

### Order Status Update (Admin)
```
Admin OrderDetailPage
    └── updateStatus()
        └── ordersStore.updateOrderStatus(id, status)
            ├── api.patch(`/orders/${id}/status`)
            └── update local orders array
```

### Promo Code Validation
```
CartSummary
    └── applyPromo()
        └── cartStore.applyPromoCode(code)
            └── api.post('/promo-codes/validate', { code })
                ├── if valid: set discount
                └── if invalid: show error
```

## State Management

### Auth Store State
```javascript
{
  user: { id, name, email, role },
  token: 'Bearer xxx',
  loading: false,
  error: null
}
```

### Cart Store State
```javascript
{
  items: [
    { product_id, name, price, image, quantity }
  ],
  promoCode: 'SUMMER2024',
  discount: 10.00
}
```

### Products Store State
```javascript
{
  products: [...],
  categories: [...],
  currentProduct: { ... },
  filters: {
    search: '',
    category_id: null,
    min_price: null,
    max_price: null,
    sort: 'name'
  }
}
```

### Orders Store State
```javascript
{
  orders: [...],
  currentOrder: { ... },
  loading: false,
  error: null
}
```

## Route Guards

```
Router Navigation
    │
    ├── Public Route? → Allow
    │
    ├── Requires Auth?
    │   ├── Not authenticated → Redirect to /login
    │   └── Authenticated → Continue
    │
    ├── Requires Admin?
    │   ├── Not admin → Redirect to /
    │   └── Admin → Allow
    │
    └── Requires Picker?
        ├── Not picker → Redirect to /
        └── Picker → Allow
```

## Component Communication

### Parent → Child (Props)
```
ProductsPage → ProductCard
  :product="product"

CartPage → CartItem
  :item="item"

DashboardPage → OrderCard
  :order="order"
```

### Child → Parent (Events)
```
AlertMessage
  @dismiss → parent handles

CartItem
  internal: calls store directly
  no events emitted
```

### Sibling (via Store)
```
ProductCard (adds to cart)
    ↓
cartStore.addItem()
    ↓
Navbar (updates badge)
```

### Global (via Router)
```
Any Component
    ↓
router.push('/path')
    ↓
RouterView updates
```

---

**Key Principles:**
- Components stay focused and reusable
- State flows down via props
- Actions flow up via events or stores
- Stores handle cross-component state
- Router handles navigation
- API service centralizes HTTP calls
