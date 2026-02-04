# Installation Guide - Product Store Docker Infrastructure

This guide will help you set up the complete Docker infrastructure for the Product Store e-commerce application.

## Prerequisites

Before you begin, ensure you have the following installed:

- **Docker Engine** 20.10 or higher
- **Docker Compose** v2.0 or higher (or docker-compose v1.29+)
- **Git** (for cloning the repository)
- At least **4GB RAM** available for Docker
- At least **10GB** of free disk space

### Verify Prerequisites

```bash
# Check Docker
docker --version
# Should show: Docker version 20.10+ or higher

# Check Docker Compose (v2)
docker compose version
# OR (v1)
docker-compose --version
# Should show version 2.0+ or 1.29+

# Check available disk space
df -h
```

## Installation Steps

### Step 1: Clone the Repository

```bash
git clone <repository-url>
cd product-store
```

### Step 2: Validate the Infrastructure

Before proceeding, validate that all Docker files are correctly set up:

```bash
chmod +x validate.sh setup.sh
./validate.sh
```

You should see:
```
✓ All checks passed! Infrastructure is ready.
```

### Step 3: Configure Environment

Create your environment file from the template:

```bash
cp .env.example .env
```

**Edit `.env` if needed** (optional for development):
```bash
nano .env  # or use your preferred editor
```

Key settings to review:
- `APP_NAME` - Your application name
- `DB_PASSWORD` - Database password (change for production!)
- Port mappings if defaults conflict with existing services

### Step 4: Automated Setup (Recommended)

Run the automated setup script:

```bash
./setup.sh
```

This script will:
1. ✅ Build all Docker images
2. ✅ Start all services
3. ✅ Install Laravel dependencies
4. ✅ Generate application key
5. ✅ Run database migrations
6. ✅ Set up storage links
7. ✅ Configure permissions

**Expected output:**
```
╔════════════════════════════════════════════════╗
║          Setup Complete! 🎉                    ║
╔════════════════════════════════════════════════╗
```

### Step 5: Verify Installation

Check that all services are running:

```bash
docker compose ps
```

All services should show status as **Up** and **healthy**:
```
NAME                      STATUS                    PORTS
product_store_backend     Up (healthy)             9000/tcp
product_store_frontend    Up (healthy)             0.0.0.0:3000->3000/tcp
product_store_nginx       Up (healthy)             0.0.0.0:8000->80/tcp
product_store_postgres    Up (healthy)             0.0.0.0:5432->5432/tcp
```

Test the health endpoint:
```bash
curl http://localhost:8000/health
```

Should return:
```
healthy
```

## Alternative: Manual Setup

If you prefer manual control, follow these steps:

### 1. Build Images

```bash
docker compose build
```

### 2. Start Services

```bash
docker compose up -d
```

### 3. Install Laravel Dependencies

```bash
docker compose exec backend composer install --no-interaction --prefer-dist
```

### 4. Generate Application Key

```bash
docker compose exec backend php artisan key:generate
```

### 5. Run Migrations

```bash
docker compose exec backend php artisan migrate
```

### 6. Create Storage Link

```bash
docker compose exec backend php artisan storage:link
```

### 7. Set Permissions

```bash
docker compose exec backend chown -R www-data:www-data storage bootstrap/cache
docker compose exec backend chmod -R 775 storage bootstrap/cache
```

## Using Make Commands (Alternative)

If you prefer using Make:

```bash
# Complete setup in one command
make setup

# Or step by step
make build
make up
make install
make key-generate
make migrate
make storage-link
```

View all available commands:
```bash
make help
```

## Access Your Application

After successful installation:

| Service | URL | Description |
|---------|-----|-------------|
| **Laravel API** | http://localhost:8000 | Backend REST API |
| **Vue.js Frontend** | http://localhost:3000 | Frontend application |
| **Database** | localhost:5432 | PostgreSQL (use DB client) |
| **Health Check** | http://localhost:8000/health | Nginx health status |

## Post-Installation Tasks

### 1. Seed the Database (Optional)

If you have seeders configured:

```bash
docker compose exec backend php artisan db:seed
```

Or using Make:
```bash
make seed
```

### 2. Install Frontend Dependencies (If Needed)

```bash
docker compose exec frontend npm install
```

### 3. Verify Logs

Check that services are running without errors:

```bash
# All services
docker compose logs

# Specific service
docker compose logs backend
docker compose logs frontend
docker compose logs nginx
docker compose logs postgres
```

## Common Issues & Solutions

### Issue: Port Already in Use

**Error:** `Bind for 0.0.0.0:8000 failed: port is already allocated`

**Solution:**
1. Check what's using the port:
   ```bash
   sudo lsof -i :8000
   ```
2. Either stop the conflicting service or change the port in `.env`:
   ```
   NGINX_PORT=8001
   ```
3. Restart:
   ```bash
   docker compose down
   docker compose up -d
   ```

### Issue: Permission Denied

**Error:** Permission errors when running commands

**Solution:**
```bash
# Fix Laravel storage permissions
docker compose exec backend chown -R www-data:www-data storage bootstrap/cache
docker compose exec backend chmod -R 775 storage bootstrap/cache

# Fix script permissions
chmod +x setup.sh validate.sh
```

### Issue: Database Connection Failed

**Error:** `SQLSTATE[08006] [7] could not connect to server`

**Solution:**
1. Check PostgreSQL is running:
   ```bash
   docker compose ps postgres
   ```
2. Check logs:
   ```bash
   docker compose logs postgres
   ```
3. Verify database credentials in `.env` match those in `docker-compose.yml`

### Issue: Services Not Healthy

**Error:** Services showing as unhealthy

**Solution:**
```bash
# Check service logs
docker compose logs <service-name>

# Restart service
docker compose restart <service-name>

# Full restart
docker compose down
docker compose up -d
```

### Issue: Build Fails

**Error:** Build errors during image creation

**Solution:**
```bash
# Clean build without cache
docker compose build --no-cache

# Or using Make
make build-clean
```

## Updating the Infrastructure

### Pull Latest Changes

```bash
git pull
docker compose down
docker compose build
docker compose up -d
docker compose exec backend composer install
docker compose exec backend php artisan migrate
```

### Rebuild Specific Service

```bash
docker compose build backend
docker compose up -d backend
```

## Uninstallation

### Stop Services (Keep Data)

```bash
docker compose down
```

### Complete Removal (Including Data)

**⚠️ WARNING: This will delete all database data and volumes!**

```bash
docker compose down -v
```

### Remove Images

```bash
docker compose down --rmi all -v
```

## Getting Help

### View Logs

```bash
# All services
docker compose logs -f

# Specific service
docker compose logs -f backend

# Last 100 lines
docker compose logs --tail=100 backend
```

### Access Container Shell

```bash
# Backend
docker compose exec backend sh

# Frontend
docker compose exec frontend sh

# Database
docker compose exec postgres sh
```

### Check Service Status

```bash
docker compose ps
```

### View Resource Usage

```bash
docker stats
```

## Next Steps

After successful installation:

1. **Set up Laravel application code** in `backend/` directory
2. **Set up Vue.js application code** in `frontend/` directory
3. **Configure CORS** in Laravel for frontend communication
4. **Create API routes** in `backend/routes/api.php`
5. **Develop database models and migrations**
6. **Build Vue.js components**
7. **Configure authentication** (Laravel Sanctum/Passport)

## Additional Resources

- [Docker Documentation](DOCKER.md) - Detailed Docker configuration
- [Setup Summary](SETUP_SUMMARY.md) - Complete infrastructure reference
- [Makefile Commands](Makefile) - All available make targets
- Main [README](README.md) - Project overview

## Support

If you encounter issues not covered in this guide:

1. Check the logs: `docker compose logs -f`
2. Validate setup: `./validate.sh`
3. Review [DOCKER.md](DOCKER.md) for detailed configuration
4. Check Docker and Docker Compose versions are up to date

---

**Installation Guide Version**: 1.0  
**Last Updated**: 2024  
**Tested On**: Docker 28.0+, Docker Compose v2.38+
