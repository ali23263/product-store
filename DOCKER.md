# Docker Infrastructure Documentation

Complete Docker setup for the Product Store e-commerce application.

## 🏗️ Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                        Client Browser                        │
└────────────────┬────────────────────────┬───────────────────┘
                 │                        │
        Port 8000│                        │Port 3000
                 │                        │
        ┌────────▼────────┐      ┌───────▼────────┐
        │  Nginx (Alpine) │      │ Vue.js (Node18)│
        │  Reverse Proxy  │      │  Dev Server    │
        └────────┬────────┘      └────────────────┘
                 │
                 │Port 9000
                 │
        ┌────────▼──────────┐
        │ Laravel (PHP 8.2) │
        │     PHP-FPM       │
        └────────┬──────────┘
                 │
                 │Port 5432
                 │
        ┌────────▼──────────┐
        │ PostgreSQL 15     │
        │    Database       │
        └───────────────────┘
```

## 📦 Services Overview

### PostgreSQL 15
- **Image**: postgres:15-alpine
- **Port**: 5432
- **Credentials**: product_user / secret123
- **Database**: product_store
- **Health Check**: ✅ Enabled

### Laravel Backend (PHP 8.2)
- **Base**: php:8.2-fpm-alpine
- **Extensions**: pdo_pgsql, pgsql, mbstring, bcmath, gd, zip, opcache, redis, and more
- **Composer**: 2.7 pre-installed
- **Health Check**: ✅ PHP-FPM ping endpoint

### Nginx Reverse Proxy
- **Image**: nginx:1.25-alpine
- **Port**: 8000 (external)
- **Features**: Gzip, security headers, rate limiting, caching
- **Health Check**: ✅ HTTP endpoint

### Vue.js Frontend (Node 18)
- **Base**: node:18-alpine
- **Port**: 3000 (development server)
- **Features**: Hot Module Replacement, Vite
- **Health Check**: ✅ HTTP endpoint

## 🚀 Quick Start

```bash
# 1. Create environment file
cp .env.example .env

# 2. Build and start all services
docker-compose up -d --build

# 3. Install Laravel dependencies
docker-compose exec backend composer install

# 4. Generate Laravel app key
docker-compose exec backend php artisan key:generate

# 5. Run migrations
docker-compose exec backend php artisan migrate

# 6. Install frontend dependencies (if needed)
docker-compose exec frontend npm install
```

## 🌐 Access Points

- **Laravel API**: http://localhost:8000
- **Vue.js Frontend**: http://localhost:3000
- **PostgreSQL**: localhost:5432
- **Health Check**: http://localhost:8000/health

## 🔧 Common Commands

### Service Management
```bash
docker-compose up -d          # Start all services
docker-compose down           # Stop all services
docker-compose ps             # List running services
docker-compose logs -f        # Follow all logs
```

### Backend Commands
```bash
docker-compose exec backend php artisan migrate
docker-compose exec backend php artisan cache:clear
docker-compose exec backend composer install
docker-compose exec backend php artisan test
```

### Frontend Commands
```bash
docker-compose exec frontend npm install
docker-compose exec frontend npm run build
docker-compose exec frontend npm run lint
```

### Database Commands
```bash
docker-compose exec postgres psql -U product_user -d product_store
docker-compose exec postgres pg_dump -U product_user product_store > backup.sql
```

## 📁 File Structure

```
product-store/
├── docker-compose.yml          # Main orchestration file
├── .env.example                # Environment template
├── backend/
│   ├── Dockerfile              # PHP 8.2 FPM with extensions
│   └── .dockerignore          # Build exclusions
├── frontend/
│   ├── Dockerfile              # Node 18 for Vue.js
│   └── .dockerignore          # Build exclusions
└── docker/
    └── nginx/
        └── default.conf        # Nginx reverse proxy config
```

## 🔐 Environment Variables

See `.env.example` for all available variables:

```env
# Database
DB_DATABASE=product_store
DB_USERNAME=product_user
DB_PASSWORD=secret123

# Ports
NGINX_PORT=8000
FRONTEND_PORT=3000

# PHP Settings
PHP_MEMORY_LIMIT=512M
PHP_UPLOAD_MAX_FILESIZE=20M
```

## 📊 Named Volumes

- `product_store_postgres_data` - Database persistence
- `product_store_backend_vendor` - Composer packages
- `product_store_backend_storage` - Laravel storage
- `product_store_frontend_node_modules` - NPM packages
- `product_store_nginx_logs` - Web server logs

## 🛠️ Troubleshooting

### Permission Issues
```bash
docker-compose exec backend chown -R www-data:www-data storage bootstrap/cache
docker-compose exec backend chmod -R 775 storage bootstrap/cache
```

### Rebuild Service
```bash
docker-compose build --no-cache backend
docker-compose up -d backend
```

### Clean Restart
```bash
docker-compose down -v  # ⚠️ Deletes volumes!
docker-compose up -d --build
```

## 🔍 Health Checks

All services include automatic health monitoring:
- PostgreSQL: Every 10s
- PHP-FPM: Every 10s  
- Nginx: Every 10s
- Frontend: Every 30s

Check health status:
```bash
docker-compose ps
```

## 📝 Best Practices

✅ Never commit `.env` file  
✅ Use `.dockerignore` to optimize builds  
✅ Run containers as non-root users  
✅ Keep images updated regularly  
✅ Enable OpCache in production  
✅ Use strong passwords in production  

## 🚀 Production Notes

For production deployment:
1. Set `APP_ENV=production`, `APP_DEBUG=false`
2. Add SSL/TLS certificates to Nginx
3. Use Docker secrets for sensitive data
4. Build Vue.js production bundle
5. Enable Redis for caching
6. Configure automated backups
7. Add monitoring (Prometheus/Grafana)

---

**Stack**: Laravel 10+ | Vue.js 3 | PostgreSQL 15 | Nginx | Docker
