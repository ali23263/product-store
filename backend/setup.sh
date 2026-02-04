#!/bin/bash

# Colors for output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo -e "${GREEN}================================${NC}"
echo -e "${GREEN}Laravel Backend Setup${NC}"
echo -e "${GREEN}================================${NC}"
echo ""

# Check if .env exists
if [ ! -f .env ]; then
    echo -e "${YELLOW}Creating .env file...${NC}"
    cp .env.example .env
else
    echo -e "${GREEN}.env file already exists${NC}"
fi

# Install composer dependencies
echo -e "${YELLOW}Installing composer dependencies...${NC}"
if command -v composer &> /dev/null; then
    composer install --optimize-autoloader
else
    echo -e "${RED}Composer not found! Please install composer first.${NC}"
    exit 1
fi

# Generate application key
echo -e "${YELLOW}Generating application key...${NC}"
php artisan key:generate

# Check database connection
echo -e "${YELLOW}Testing database connection...${NC}"
if php artisan db:show &> /dev/null; then
    echo -e "${GREEN}Database connection successful!${NC}"
else
    echo -e "${RED}Database connection failed!${NC}"
    echo -e "${YELLOW}Please check your database configuration in .env${NC}"
    exit 1
fi

# Run migrations
echo -e "${YELLOW}Running migrations...${NC}"
php artisan migrate --force

# Seed database
echo -e "${YELLOW}Seeding database...${NC}"
php artisan db:seed --force

# Create storage link
echo -e "${YELLOW}Creating storage link...${NC}"
php artisan storage:link

# Clear and cache configuration
echo -e "${YELLOW}Optimizing application...${NC}"
php artisan config:clear
php artisan route:clear
php artisan cache:clear

echo ""
echo -e "${GREEN}================================${NC}"
echo -e "${GREEN}Setup Complete!${NC}"
echo -e "${GREEN}================================${NC}"
echo ""
echo -e "Default users created:"
echo -e "  ${GREEN}Admin:${NC} admin@admin.com / password"
echo -e "  ${GREEN}Picker:${NC} picker@picker.com / password"
echo -e "  ${GREEN}Customer:${NC} customer@customer.com / password"
echo ""
echo -e "Default promo codes:"
echo -e "  ${GREEN}WELCOME10${NC} - 10% off (100 uses)"
echo -e "  ${GREEN}SUMMER20${NC} - 20% off (50 uses)"
echo -e "  ${GREEN}MEGA50${NC} - 50% off (10 uses)"
echo ""
echo -e "${GREEN}You can now start the application!${NC}"
