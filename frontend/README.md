# E-Commerce Frontend

Vue.js 3 frontend for the e-commerce platform.

## Setup

```bash
# Install dependencies
npm install

# Copy environment file
cp .env.example .env

# Update .env with your API URL
VITE_API_URL=http://localhost:8000/api

# Start development server
npm run dev
```

## Features

- Vue 3 with Composition API
- Pinia state management
- Vue Router with role-based guards
- Tailwind CSS for styling
- Axios for API calls
- Role-based interfaces (Customer, Admin, Picker)

## User Roles

- **Customer**: Browse products, manage cart, place orders, view order history
- **Admin**: Manage products, categories, orders, users, and promo codes
- **Picker**: View and process orders for fulfillment

## Build for Production

```bash
npm run build
```

The built files will be in the `dist` directory.
