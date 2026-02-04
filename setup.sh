#!/bin/bash

# Product Store - Quick Setup Script
# This script helps you quickly set up the Docker environment

set -e

# Colors
BLUE='\033[0;34m'
GREEN='\033[0;32m'
YELLOW='\033[0;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo -e "${BLUE}╔════════════════════════════════════════════════╗${NC}"
echo -e "${BLUE}║   Product Store - Docker Setup Script         ║${NC}"
echo -e "${BLUE}╔════════════════════════════════════════════════╗${NC}"
echo ""

# Check if Docker is installed
if ! command -v docker &> /dev/null; then
    echo -e "${RED}❌ Docker is not installed. Please install Docker first.${NC}"
    exit 1
fi

# Check if Docker Compose is installed (v1 or v2)
if ! command -v docker-compose &> /dev/null && ! docker compose version &> /dev/null; then
    echo -e "${RED}❌ Docker Compose is not installed. Please install Docker Compose first.${NC}"
    exit 1
fi

# Determine which Docker Compose command to use
if command -v docker-compose &> /dev/null; then
    COMPOSE_CMD="docker-compose"
else
    COMPOSE_CMD="docker compose"
fi

echo -e "${GREEN}✓ Docker is installed${NC}"
echo -e "${GREEN}✓ Docker Compose is installed (using: $COMPOSE_CMD)${NC}"
echo ""

# Create .env file if it doesn't exist
if [ ! -f .env ]; then
    echo -e "${YELLOW}Creating .env file from .env.example...${NC}"
    cp .env.example .env
    echo -e "${GREEN}✓ .env file created${NC}"
else
    echo -e "${GREEN}✓ .env file already exists${NC}"
fi

echo ""
echo -e "${BLUE}Building Docker images...${NC}"
$COMPOSE_CMD build

echo ""
echo -e "${BLUE}Starting services...${NC}"
$COMPOSE_CMD up -d

echo ""
echo -e "${YELLOW}Waiting for services to be ready...${NC}"
sleep 10

echo ""
echo -e "${BLUE}Installing Laravel dependencies...${NC}"
$COMPOSE_CMD exec -T backend composer install --no-interaction --prefer-dist --optimize-autoloader

echo ""
echo -e "${BLUE}Generating Laravel application key...${NC}"
$COMPOSE_CMD exec -T backend php artisan key:generate --force

echo ""
echo -e "${BLUE}Running database migrations...${NC}"
$COMPOSE_CMD exec -T backend php artisan migrate --force

echo ""
echo -e "${BLUE}Creating storage symbolic link...${NC}"
$COMPOSE_CMD exec -T backend php artisan storage:link || true

echo ""
echo -e "${BLUE}Setting proper permissions...${NC}"
$COMPOSE_CMD exec -T backend chown -R www-data:www-data storage bootstrap/cache
$COMPOSE_CMD exec -T backend chmod -R 775 storage bootstrap/cache

echo ""
echo -e "${GREEN}╔════════════════════════════════════════════════╗${NC}"
echo -e "${GREEN}║          Setup Complete! 🎉                    ║${NC}"
echo -e "${GREEN}╔════════════════════════════════════════════════╗${NC}"
echo ""
echo -e "${YELLOW}Access your application:${NC}"
echo -e "  ${BLUE}Laravel API:${NC}      http://localhost:8000"
echo -e "  ${BLUE}Vue.js Frontend:${NC}  http://localhost:3000"
echo -e "  ${BLUE}Health Check:${NC}     http://localhost:8000/health"
echo ""
echo -e "${YELLOW}Useful commands:${NC}"
echo -e "  ${BLUE}View logs:${NC}        $COMPOSE_CMD logs -f"
echo -e "  ${BLUE}Stop services:${NC}    $COMPOSE_CMD down"
echo -e "  ${BLUE}Restart:${NC}          $COMPOSE_CMD restart"
echo -e "  ${BLUE}Run migrations:${NC}   $COMPOSE_CMD exec backend php artisan migrate"
echo -e "  ${BLUE}Seed database:${NC}    $COMPOSE_CMD exec backend php artisan db:seed"
echo ""
echo -e "${YELLOW}Or use the Makefile:${NC}"
echo -e "  ${BLUE}make help${NC}         Show all available commands"
echo -e "  ${BLUE}make logs${NC}         Follow logs"
echo -e "  ${BLUE}make migrate${NC}      Run migrations"
echo -e "  ${BLUE}make seed${NC}         Seed database"
echo ""
