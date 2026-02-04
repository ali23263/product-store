.PHONY: help build up down restart logs ps clean install migrate seed test

# Default target
.DEFAULT_GOAL := help

# Colors for output
BLUE := \033[0;34m
GREEN := \033[0;32m
YELLOW := \033[0;33m
RED := \033[0;31m
NC := \033[0m # No Color

help: ## Show this help message
	@echo "$(BLUE)Product Store - Docker Management$(NC)"
	@echo ""
	@echo "$(GREEN)Available commands:$(NC)"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "  $(YELLOW)%-15s$(NC) %s\n", $$1, $$2}'

build: ## Build all Docker images
	@echo "$(BLUE)Building Docker images...$(NC)"
	docker-compose build

build-clean: ## Build all Docker images without cache
	@echo "$(BLUE)Building Docker images (no cache)...$(NC)"
	docker-compose build --no-cache

up: ## Start all services
	@echo "$(BLUE)Starting services...$(NC)"
	docker-compose up -d
	@echo "$(GREEN)Services started!$(NC)"
	@echo "Laravel API: http://localhost:8000"
	@echo "Vue.js Frontend: http://localhost:3000"

down: ## Stop all services
	@echo "$(BLUE)Stopping services...$(NC)"
	docker-compose down
	@echo "$(GREEN)Services stopped!$(NC)"

restart: ## Restart all services
	@echo "$(BLUE)Restarting services...$(NC)"
	docker-compose restart
	@echo "$(GREEN)Services restarted!$(NC)"

logs: ## Show logs for all services
	docker-compose logs -f

logs-backend: ## Show backend logs
	docker-compose logs -f backend

logs-frontend: ## Show frontend logs
	docker-compose logs -f frontend

logs-nginx: ## Show nginx logs
	docker-compose logs -f nginx

logs-db: ## Show database logs
	docker-compose logs -f postgres

ps: ## List running services
	docker-compose ps

clean: ## Stop and remove all containers, networks, and volumes
	@echo "$(RED)⚠️  WARNING: This will delete all data!$(NC)"
	@read -p "Are you sure? [y/N] " -n 1 -r; \
	echo; \
	if [[ $$REPLY =~ ^[Yy]$$ ]]; then \
		docker-compose down -v; \
		echo "$(GREEN)Cleaned up!$(NC)"; \
	fi

# Backend commands
install: ## Install Laravel dependencies
	@echo "$(BLUE)Installing Laravel dependencies...$(NC)"
	docker-compose exec backend composer install
	@echo "$(GREEN)Dependencies installed!$(NC)"

key-generate: ## Generate Laravel application key
	@echo "$(BLUE)Generating application key...$(NC)"
	docker-compose exec backend php artisan key:generate
	@echo "$(GREEN)Key generated!$(NC)"

migrate: ## Run database migrations
	@echo "$(BLUE)Running migrations...$(NC)"
	docker-compose exec backend php artisan migrate
	@echo "$(GREEN)Migrations completed!$(NC)"

migrate-fresh: ## Fresh migration (drops all tables)
	@echo "$(RED)⚠️  WARNING: This will drop all tables!$(NC)"
	@read -p "Are you sure? [y/N] " -n 1 -r; \
	echo; \
	if [[ $$REPLY =~ ^[Yy]$$ ]]; then \
		docker-compose exec backend php artisan migrate:fresh; \
		echo "$(GREEN)Fresh migration completed!$(NC)"; \
	fi

seed: ## Seed the database
	@echo "$(BLUE)Seeding database...$(NC)"
	docker-compose exec backend php artisan db:seed
	@echo "$(GREEN)Database seeded!$(NC)"

test: ## Run backend tests
	@echo "$(BLUE)Running tests...$(NC)"
	docker-compose exec backend php artisan test

cache-clear: ## Clear all Laravel caches
	@echo "$(BLUE)Clearing caches...$(NC)"
	docker-compose exec backend php artisan cache:clear
	docker-compose exec backend php artisan config:clear
	docker-compose exec backend php artisan route:clear
	docker-compose exec backend php artisan view:clear
	@echo "$(GREEN)Caches cleared!$(NC)"

storage-link: ## Create storage symbolic link
	@echo "$(BLUE)Creating storage link...$(NC)"
	docker-compose exec backend php artisan storage:link
	@echo "$(GREEN)Storage link created!$(NC)"

# Frontend commands
npm-install: ## Install frontend dependencies
	@echo "$(BLUE)Installing frontend dependencies...$(NC)"
	docker-compose exec frontend npm install
	@echo "$(GREEN)Dependencies installed!$(NC)"

npm-build: ## Build frontend for production
	@echo "$(BLUE)Building frontend...$(NC)"
	docker-compose exec frontend npm run build
	@echo "$(GREEN)Frontend built!$(NC)"

npm-lint: ## Run frontend linter
	@echo "$(BLUE)Running linter...$(NC)"
	docker-compose exec frontend npm run lint

# Database commands
db-shell: ## Access PostgreSQL shell
	docker-compose exec postgres psql -U product_user -d product_store

db-backup: ## Backup database
	@echo "$(BLUE)Backing up database...$(NC)"
	docker-compose exec postgres pg_dump -U product_user product_store > backup_$$(date +%Y%m%d_%H%M%S).sql
	@echo "$(GREEN)Backup created!$(NC)"

db-restore: ## Restore database from backup (specify FILE=backup.sql)
	@if [ -z "$(FILE)" ]; then \
		echo "$(RED)Error: Please specify FILE=backup.sql$(NC)"; \
		exit 1; \
	fi
	@echo "$(BLUE)Restoring database from $(FILE)...$(NC)"
	docker-compose exec -T postgres psql -U product_user product_store < $(FILE)
	@echo "$(GREEN)Database restored!$(NC)"

# Shell access
shell-backend: ## Access backend container shell
	docker-compose exec backend sh

shell-frontend: ## Access frontend container shell
	docker-compose exec frontend sh

shell-nginx: ## Access nginx container shell
	docker-compose exec nginx sh

shell-db: ## Access database container shell
	docker-compose exec postgres sh

# Setup commands
setup: build up install key-generate migrate storage-link ## Complete initial setup
	@echo "$(GREEN)✓ Setup complete!$(NC)"
	@echo "$(YELLOW)Next steps:$(NC)"
	@echo "1. Visit http://localhost:8000 for Laravel API"
	@echo "2. Visit http://localhost:3000 for Vue.js frontend"
	@echo "3. Run 'make seed' to populate database with sample data"

dev: up logs ## Start services and follow logs

# Health check
health: ## Check service health
	@echo "$(BLUE)Checking service health...$(NC)"
	@docker-compose ps
	@echo ""
	@curl -s http://localhost:8000/health && echo " $(GREEN)✓ Nginx is healthy$(NC)" || echo " $(RED)✗ Nginx is down$(NC)"
