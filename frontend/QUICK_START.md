# Quick Start Guide

## Prerequisites
- Node.js 18+ installed
- Backend API running at `http://localhost:8000`

## Installation

```bash
# Navigate to frontend directory
cd frontend

# Install dependencies
npm install

# Create environment file
cp .env.example .env
```

## Environment Configuration

Edit `.env` file:
```env
VITE_API_URL=http://localhost:8000/api
```

## Development

```bash
# Start dev server (runs on http://localhost:3000)
npm run dev
```

## Production Build

```bash
# Build for production
npm run build

# Preview production build
npm run preview
```

## Project Overview

### User Roles & Access

**Customer** (default role)
- Browse products
- Add items to cart
- Apply promo codes
- Place orders
- View order history

**Admin**
- Full access to:
  - Dashboard with statistics
  - Product management (CRUD)
  - Category management
  - Order management
  - User management
  - Promo code generation

**Picker**
- View assigned orders
- Pick order items
- Update order status
- Mark orders as shipped

### Key Features

1. **Shopping Cart**
   - Persists in localStorage for guests
   - Syncs with server for logged-in users
   - Real-time total calculation
   - Promo code support

2. **Product Catalog**
   - Search, filter, and sort
   - Category navigation
   - Stock availability
   - Responsive grid layout

3. **Checkout Process**
   - Shipping information form
   - Order summary
   - Promo code validation
   - Order confirmation

4. **Admin Panel**
   - Statistics dashboard
   - Complete CRUD for all entities
   - Order status management
   - Promo code generator

5. **Picker Interface**
   - Order queue display
   - Interactive picking checklist
   - Progress tracking
   - Status updates

### Testing Credentials

You'll need to create these users in the backend:

```
Customer:
email: customer@example.com
password: password
role: customer

Admin:
email: admin@example.com
password: password
role: admin

Picker:
email: picker@example.com
password: password
role: picker
```

### Common Routes

**Public:**
- `/` - Home page
- `/products` - Product catalog
- `/products/:id` - Product detail
- `/cart` - Shopping cart
- `/login` - Login
- `/register` - Register

**Customer:**
- `/checkout` - Checkout
- `/dashboard` - Customer dashboard
- `/orders/:id` - Order details

**Admin:**
- `/admin/dashboard` - Admin home
- `/admin/products` - Manage products
- `/admin/orders` - Manage orders
- `/admin/promo-codes` - Manage promos
- `/admin/users` - Manage users

**Picker:**
- `/picker/orders` - Orders to pick
- `/picker/orders/:id` - Pick order

### Troubleshooting

**CORS Issues:**
- Ensure Laravel backend allows `http://localhost:3000`
- Check `cors.php` configuration

**API Connection:**
- Verify `VITE_API_URL` in `.env`
- Check backend is running
- Test API endpoints with Postman

**Auth Issues:**
- Clear localStorage: `localStorage.clear()`
- Check token in localStorage
- Verify backend returns correct user object

**Build Errors:**
- Delete `node_modules` and reinstall
- Clear Vite cache: `rm -rf .vite`
- Check for syntax errors in components

### Development Tips

1. **Hot Module Replacement (HMR)**
   - Vite automatically reloads changes
   - Component state preserved when possible

2. **Vue DevTools**
   - Install browser extension
   - Inspect Pinia stores
   - Debug router navigation

3. **API Debugging**
   - Check Network tab in DevTools
   - Axios interceptors log errors
   - Response interceptor handles 401

4. **Tailwind CSS**
   - Use browser DevTools to inspect classes
   - Refer to Tailwind docs for utilities
   - Customize in `tailwind.config.js`

### File Structure

```
src/
├── assets/          # CSS and static assets
├── components/      # Reusable components
├── router/          # Vue Router configuration
├── services/        # API service layer
├── stores/          # Pinia state management
└── views/           # Page components
```

### Code Style

- Use Composition API with `<script setup>`
- Prefer computed properties for derived state
- Use Pinia stores for shared state
- Keep components focused and single-purpose
- Use Tailwind utilities over custom CSS

### Performance Tips

- Images: Use appropriate sizes and formats
- Lazy load routes with dynamic imports
- Use `v-show` for frequently toggled elements
- Use `v-if` for conditionally rendered elements
- Debounce search inputs

### Deployment

**Static Hosting (Netlify, Vercel):**
```bash
npm run build
# Upload dist/ folder
```

**Docker:**
```dockerfile
FROM node:18-alpine
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build
FROM nginx:alpine
COPY --from=0 /app/dist /usr/share/nginx/html
```

**Environment Variables:**
- Set `VITE_API_URL` to production API
- Rebuild after changing env vars

### Support

For issues or questions:
1. Check IMPLEMENTATION_SUMMARY.md
2. Review component code
3. Check browser console for errors
4. Verify API responses in Network tab

---

**Happy Coding! 🚀**
