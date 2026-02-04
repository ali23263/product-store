# Product Store E-Commerce Platform

## 🤖 Custom GitHub Copilot Agents

This repository contains custom GitHub Copilot agents specialized for building a modern e-commerce platform with **Laravel, Vue.js, PostgreSQL, and Docker**.

### Available Agents

| Agent | Specialization |
|-------|---------------|
| `@e-commerce-backend` | Laravel API for products, cart, orders, promo codes, roles |
| `@e-commerce-frontend` | Vue.js 3 + Tailwind CSS for all UI pages and components |
| `@e-commerce-database` | PostgreSQL schema design and optimization |
| `@e-commerce-fullstack` | End-to-end integration and orchestration |

### Quick Start

```
@e-commerce-fullstack Create a complete e-commerce platform with:
- Laravel 10 backend with Sanctum auth
- Vue.js 3 + Tailwind CSS frontend
- PostgreSQL database
- Docker Compose setup
- Roles: customer, admin, picker
- Features: products, cart, orders, promo codes
```

### Documentation

See [`.github/agents/README.md`](.github/agents/README.md) for detailed agent documentation and usage examples.

---

## 🚀 Features

- **Product Catalog** with categories, images, pricing, stock
- **Shopping Cart** with guest persistence and server sync
- **Checkout Flow** with promo code validation
- **Order Management** for customers, admin, and pickers
- **Role-Based Access** (customer, admin, picker)
- **Admin Dashboard** with product/order management
- **Promo Code System** with admin generator
- **Responsive Design** (mobile-first)

## 📚 Tech Stack

| Layer | Technology |
|-------|------------|
| Frontend | Vue.js 3 + Tailwind CSS + Pinia |
| Backend | Laravel 10 + PHP 8.2 |
| Database | PostgreSQL 15 |
| Auth | Laravel Sanctum (SPA) |
| Containerization | Docker Compose |
