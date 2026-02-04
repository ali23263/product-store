.PHONY: help install migrate seed fresh test clear cache optimize

help: ## Show this help message
	@echo 'Usage: make [target]'
	@echo ''
	@echo 'Available targets:'
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "  %-15s %s\n", $$1, $$2}' $(MAKEFILE_LIST)

install: ## Install composer dependencies
	composer install --optimize-autoloader

migrate: ## Run database migrations
	php artisan migrate

seed: ## Seed the database
	php artisan db:seed

fresh: ## Fresh migration with seed
	php artisan migrate:fresh --seed

test: ## Run tests
	php artisan test

clear: ## Clear all caches
	php artisan cache:clear
	php artisan config:clear
	php artisan route:clear
	php artisan view:clear

cache: ## Cache config and routes
	php artisan config:cache
	php artisan route:cache

optimize: ## Optimize the application
	composer install --optimize-autoloader --no-dev
	php artisan config:cache
	php artisan route:cache
	php artisan view:cache

key: ## Generate application key
	php artisan key:generate

storage-link: ## Create storage symbolic link
	php artisan storage:link
