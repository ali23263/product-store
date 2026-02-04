# Product Store - Vue.js Frontend

A modern, responsive e-commerce frontend application built with Vue.js 3, Tailwind CSS, and designed to work with a Laravel backend API.

## 🚀 Features

### Customer Features
- **Product Catalog**: Browse products with search, filtering, and pagination
- **Product Details**: View detailed product information with image gallery
- **Shopping Cart**: Add, remove, and update product quantities
- **Promo Codes**: Apply discount codes at checkout
- **Order History**: Track past orders and their statuses
- **User Dashboard**: Manage account and view order history

### Admin Features
- **Dashboard**: View statistics (total orders, revenue, users, products)
- **Products Management**: Full CRUD operations for products
- **Categories Management**: Create and manage product categories
- **Orders Management**: View and update order statuses
- **Promo Codes Generator**: Create and manage promotional discount codes
- **Users Management**: View all registered users

### Order Picker Features
- **Order Processing**: View orders that need to be processed
- **Status Updates**: Update order status through the fulfillment pipeline
- **Filtering**: Filter orders by status (pending, processing, ready, shipped, delivered)
- **Order Details**: View complete order information

## 🛠️ Tech Stack

- **Vue.js 3** - Progressive JavaScript framework with Composition API
- **Vue Router** - Official routing library for Vue.js
- **Pinia** - State management library
- **Axios** - HTTP client for API requests
- **Tailwind CSS** - Utility-first CSS framework
- **Vite** - Next generation frontend tooling

## 📋 Prerequisites

- Node.js 16+ and npm
- Laravel backend API running on `http://localhost:8000`

## 🔧 Installation

1. **Navigate to the frontend directory**:
   ```bash
   cd frontend
   ```

2. **Install dependencies**:
   ```bash
   npm install
   ```

3. **Configure API endpoint** (if needed):
   - Edit `src/services/api.js`
   - Update `baseURL` to match your backend API URL

## 🚀 Development

Start the development server:

```bash
npm run dev
```

The application will be available at `http://localhost:3000`

## 🏗️ Build for Production

Build the application for production:

```bash
npm run build
```

The build artifacts will be stored in the `dist/` directory.

## 📁 Project Structure

```
frontend/
├── public/              # Static assets
├── src/
│   ├── assets/         # CSS and other assets
│   │   └── main.css    # Tailwind CSS imports
│   ├── components/     # Reusable Vue components
│   │   ├── admin/      # Admin-specific components
│   │   ├── CartItem.vue
│   │   ├── Footer.vue
│   │   ├── Header.vue
│   │   ├── LoadingSpinner.vue
│   │   ├── Modal.vue
│   │   ├── OrderCard.vue
│   │   ├── ProductCard.vue
│   │   └── Toast.vue
│   ├── layouts/        # Layout components
│   │   ├── AdminLayout.vue
│   │   ├── AuthLayout.vue
│   │   └── MainLayout.vue
│   ├── pages/          # Page components
│   │   ├── Admin.vue
│   │   ├── Cart.vue
│   │   ├── Dashboard.vue
│   │   ├── Home.vue
│   │   ├── Login.vue
│   │   ├── Picker.vue
│   │   ├── ProductDetail.vue
│   │   └── Register.vue
│   ├── router/         # Vue Router configuration
│   │   └── index.js
│   ├── services/       # API services
│   │   └── api.js
│   ├── stores/         # Pinia stores
│   │   ├── auth.js
│   │   ├── cart.js
│   │   ├── orders.js
│   │   └── products.js
│   ├── utils/          # Utility functions
│   │   ├── helpers.js
│   │   └── toast.js
│   ├── App.vue         # Root component
│   └── main.js         # Application entry point
├── index.html
├── package.json
├── tailwind.config.js
├── vite.config.js
└── postcss.config.js
```

## 🎨 Design Features

### Color Scheme
- Primary: Indigo/Purple gradient
- Accent: Purple-600
- Success: Green
- Error: Red
- Warning: Yellow

### Responsive Design
- Mobile-first approach
- Breakpoints: sm (640px), md (768px), lg (1024px), xl (1280px), 2xl (1536px)
- Fully responsive on all screen sizes

### UI Components
- Custom button styles (btn-primary, btn-secondary)
- Input fields with focus states
- Card components with hover effects
- Toast notifications
- Modal dialogs
- Loading spinners

## 🔐 Authentication

The application uses token-based authentication with Laravel Sanctum:

1. Login/Register creates a token
2. Token is stored in localStorage
3. Token is automatically added to all API requests
4. Automatic logout on 401 responses

### Protected Routes
- `/dashboard` - Requires customer role
- `/admin` - Requires admin role
- `/picker` - Requires picker role

## 🛒 Shopping Cart

The shopping cart:
- Persists in localStorage
- Updates in real-time
- Shows item count in header
- Allows quantity adjustments
- Supports promo code application

## 📦 State Management

### Stores (Pinia)

**Auth Store** (`stores/auth.js`)
- User authentication state
- Login/Register/Logout
- Role-based permissions

**Cart Store** (`stores/cart.js`)
- Shopping cart items
- Add/Remove/Update items
- Cart total calculation
- Persistent storage

**Products Store** (`stores/products.js`)
- Product catalog
- Categories
- Filters and search
- CRUD operations (admin)

**Orders Store** (`stores/orders.js`)
- Order history
- Order creation
- Promo code application
- Status updates

## 🌐 API Integration

All API calls are centralized in `src/services/api.js`:

### Endpoints
- **Auth**: `/api/login`, `/api/register`, `/api/logout`
- **Products**: `/api/products`, `/api/products/{id}`
- **Categories**: `/api/categories`
- **Orders**: `/api/orders`, `/api/orders/{id}`
- **Promo Codes**: `/api/promo-codes`
- **Users**: `/api/users` (admin)
- **Stats**: `/api/stats/dashboard` (admin)

### Error Handling
- Automatic 401 redirect to login
- Toast notifications for errors
- Form validation errors display

## 🎯 User Roles

### Customer
- Browse and search products
- Add items to cart
- Apply promo codes
- Place orders
- View order history

### Admin
- Full dashboard access
- Manage products and categories
- Create promo codes
- View all orders and users
- Update order statuses

### Picker
- View orders to process
- Update order status through fulfillment pipeline
- Filter orders by status

## 🧪 Key Features Implementation

### Promo Code System
- Admin can generate random codes
- Set discount percentage
- Define validity period
- Set usage limits
- Customers apply at checkout
- Automatic discount calculation

### Order Status Pipeline
```
Pending → Processing → Ready → Shipped → Delivered
```

### Real-time Cart Updates
- Item count badge in header
- Instant price calculations
- Stock validation
- Persistent across sessions

## 🔄 Navigation Flow

```
Landing (/) 
  → Product Detail (/product/:id)
  → Cart (/cart)
  → Login (/login) [if not authenticated]
  → Dashboard (/dashboard) [after checkout]

Login/Register
  → Redirects based on role:
     - Customer → Home (/)
     - Admin → Admin Panel (/admin)
     - Picker → Picker Dashboard (/picker)
```

## 🎨 Customization

### Tailwind Configuration
Customize colors, fonts, and styles in `tailwind.config.js`

### API Base URL
Change backend URL in `src/services/api.js`:
```javascript
baseURL: 'http://your-backend-url/api'
```

## 🐛 Troubleshooting

**CORS Issues**
- Ensure Laravel backend has CORS configured
- Check `withCredentials: true` in axios config

**API Connection Failed**
- Verify backend is running on port 8000
- Check network tab for actual API errors
- Verify token is being sent in headers

**Build Errors**
- Clear node_modules and reinstall: `rm -rf node_modules && npm install`
- Clear Vite cache: `rm -rf node_modules/.vite`

## 📝 License

MIT License

## 👥 Credits

Built with ❤️ using Vue.js 3 and Tailwind CSS
