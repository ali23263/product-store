# Custom Agents for Product Store E-Commerce Platform

This directory contains custom GitHub Copilot agents specialized for building a modern e-commerce platform with Laravel, Vue.js, PostgreSQL, and Docker.

## 🤖 Available Agents

### 1. [e-commerce-backend](./e-commerce-backend.agent.md)
**Expert Laravel developer for e-commerce**

Specializes in:
- Product catalog management
- Shopping cart & orders
- Promo codes & discounts
- Role-based authentication (customer, admin, picker)
- RESTful API design
- PostgreSQL integration

Use for: Backend features, database migrations, API endpoints, business logic

---

### 2. [e-commerce-frontend](./e-commerce-frontend.agent.md)
**Expert Vue.js 3 + Tailwind CSS developer**

Specializes in:
- Product catalog UI
- Shopping cart & checkout
- User authentication pages
- Customer dashboard
- Admin panel interface
- Order picker interface
- Pinia state management
- Responsive design

Use for: Frontend components, pages, state management, API integration

---

### 3. [e-commerce-database](./e-commerce-database.agent.md)
**PostgreSQL database expert for e-commerce**

Specializes in:
- Database schema design
- Product & order data models
- Performance optimization
- Indexing strategies
- Data integrity & constraints
- Migration design

Use for: Database structure, migrations, performance optimization, seeders

---

### 4. [e-commerce-fullstack](./e-commerce-fullstack.agent.md)
**Full-stack integrator for complete e-commerce platform**

Specializes in:
- End-to-end feature coordination
- Authentication flow (Sanctum + Vue.js)
- API integration between Laravel and Vue.js
- Docker orchestration
- Deployment strategy
- Cross-layer debugging

Use for: Complete features that span all layers, integration work, architecture decisions

---

## 🎯 How to Use

### In GitHub Copilot Chat on GitHub.com

```
@e-commerce-backend Create product API with categories, images, and inventory tracking
```

```
@e-commerce-frontend Build shopping cart page with quantity controls and real-time updates
```

```
@e-commerce-database Design promo codes table with expiration and usage limits
```

```
@e-commerce-fullstack Implement complete checkout flow with promo code validation
```

### In VS Code with GitHub Copilot

1. Open the Copilot Chat panel
2. Type `@` followed by the agent name
3. Describe your task
4. Agent will provide specialized assistance

## 🚀 Quick Examples

### Creating a New Product Feature

```
@e-commerce-database Create products table with categories, pricing, stock
@e-commerce-backend Implement product CRUD API with validation
@e-commerce-frontend Build product listing page with filters and add-to-cart
@e-commerce-fullstack Test complete flow and fix any integration issues
```

### Adding Promo Codes

```
@e-commerce-database Design promo_codes table with expiration and usage tracking
@e-commerce-backend Implement promo code validation in checkout flow
@e-commerce-frontend Add promo code input field to checkout page
@e-commerce-fullstack Coordinate cart sync and discount application
```

## ⚡ Efficiency Features

All agents are configured with **Efficiency Guidelines** to avoid:

- ❌ Running tests multiple times "just to be sure"
- ❌ Creating verbose documentation after every change
- ❌ Running code review on own changes
- ❌ Over-engineering and premature optimization
- ❌ Analysis paralysis on small decisions

Instead, agents:

- ✅ Test once, move on
- ✅ Commit logical units of work
- ✅ Focus on shipping working code
- ✅ Iterate based on feedback
- ✅ Prefer simple solutions over complex ones

## 📚 Tech Stack

| Layer | Technology |
|-------|------------|
| Frontend | Vue.js 3 + Tailwind CSS + Pinia |
| Backend | Laravel 10 + PHP 8.2 |
| Database | PostgreSQL 15 |
| Auth | Laravel Sanctum (SPA) |
| Containerization | Docker Compose |
| Web Server | Nginx |

## 🎨 Roles

The platform supports three user roles:

1. **Customer** - Browse products, add to cart, checkout, view orders
2. **Admin** - Manage products, categories, orders, users, generate promo codes
3. **Picker** - View pending orders, mark items as picked, update order status

## 🔧 Configuration

Agents use `Claude Opus 4.5 (copilot)` model for optimal performance and accuracy.

For customization, edit the `.agent.md` files directly with your project-specific requirements.
