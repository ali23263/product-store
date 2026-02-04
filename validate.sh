#!/bin/bash

# Docker Infrastructure Validation Script
# Validates the completeness and correctness of the Docker setup

set -e

# Colors
BLUE='\033[0;34m'
GREEN='\033[0;32m'
YELLOW='\033[0;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo -e "${BLUE}╔════════════════════════════════════════════════╗${NC}"
echo -e "${BLUE}║   Docker Infrastructure Validation            ║${NC}"
echo -e "${BLUE}╔════════════════════════════════════════════════╗${NC}"
echo ""

ERRORS=0

# Function to check file exists
check_file() {
    if [ -f "$1" ]; then
        echo -e "${GREEN}✓${NC} $1"
    else
        echo -e "${RED}✗${NC} $1 ${RED}(missing)${NC}"
        ((ERRORS++))
    fi
}

# Function to check directory exists
check_dir() {
    if [ -d "$1" ]; then
        echo -e "${GREEN}✓${NC} $1/"
    else
        echo -e "${RED}✗${NC} $1/ ${RED}(missing)${NC}"
        ((ERRORS++))
    fi
}

echo -e "${YELLOW}Checking directory structure...${NC}"
check_dir "backend"
check_dir "frontend"
check_dir "docker"
check_dir "docker/nginx"
echo ""

echo -e "${YELLOW}Checking Docker configuration files...${NC}"
check_file "docker-compose.yml"
check_file "docker-compose.prod.yml"
check_file ".env.example"
check_file ".gitignore"
echo ""

echo -e "${YELLOW}Checking backend files...${NC}"
check_file "backend/Dockerfile"
check_file "backend/.dockerignore"
echo ""

echo -e "${YELLOW}Checking frontend files...${NC}"
check_file "frontend/Dockerfile"
check_file "frontend/.dockerignore"
echo ""

echo -e "${YELLOW}Checking Nginx configuration...${NC}"
check_file "docker/nginx/default.conf"
echo ""

echo -e "${YELLOW}Checking documentation files...${NC}"
check_file "DOCKER.md"
check_file "Makefile"
check_file "setup.sh"
echo ""

echo -e "${YELLOW}Validating docker-compose.yml structure...${NC}"
if [ -f "docker-compose.yml" ]; then
    # Check for required services
    if grep -q "postgres:" docker-compose.yml; then
        echo -e "${GREEN}✓${NC} PostgreSQL service defined"
    else
        echo -e "${RED}✗${NC} PostgreSQL service missing"
        ((ERRORS++))
    fi
    
    if grep -q "backend:" docker-compose.yml; then
        echo -e "${GREEN}✓${NC} Backend service defined"
    else
        echo -e "${RED}✗${NC} Backend service missing"
        ((ERRORS++))
    fi
    
    if grep -q "nginx:" docker-compose.yml; then
        echo -e "${GREEN}✓${NC} Nginx service defined"
    else
        echo -e "${RED}✗${NC} Nginx service missing"
        ((ERRORS++))
    fi
    
    if grep -q "frontend:" docker-compose.yml; then
        echo -e "${GREEN}✓${NC} Frontend service defined"
    else
        echo -e "${RED}✗${NC} Frontend service missing"
        ((ERRORS++))
    fi
    
    # Check for volumes
    if grep -q "volumes:" docker-compose.yml; then
        echo -e "${GREEN}✓${NC} Named volumes configured"
    else
        echo -e "${YELLOW}⚠${NC} No named volumes found"
    fi
    
    # Check for networks
    if grep -q "networks:" docker-compose.yml; then
        echo -e "${GREEN}✓${NC} Networks configured"
    else
        echo -e "${YELLOW}⚠${NC} No custom networks found"
    fi
    
    # Check for health checks
    if grep -q "healthcheck:" docker-compose.yml; then
        echo -e "${GREEN}✓${NC} Health checks configured"
    else
        echo -e "${YELLOW}⚠${NC} No health checks found"
    fi
fi
echo ""

echo -e "${YELLOW}Validating backend Dockerfile...${NC}"
if [ -f "backend/Dockerfile" ]; then
    if grep -q "FROM php:8.2-fpm" backend/Dockerfile; then
        echo -e "${GREEN}✓${NC} PHP 8.2 FPM base image"
    else
        echo -e "${RED}✗${NC} PHP 8.2 FPM base image not found"
        ((ERRORS++))
    fi
    
    if grep -q "pdo_pgsql" backend/Dockerfile && grep -q "pgsql" backend/Dockerfile; then
        echo -e "${GREEN}✓${NC} PostgreSQL extensions included"
    else
        echo -e "${RED}✗${NC} PostgreSQL extensions missing"
        ((ERRORS++))
    fi
    
    if grep -q "composer" backend/Dockerfile; then
        echo -e "${GREEN}✓${NC} Composer installation found"
    else
        echo -e "${RED}✗${NC} Composer installation missing"
        ((ERRORS++))
    fi
fi
echo ""

echo -e "${YELLOW}Validating frontend Dockerfile...${NC}"
if [ -f "frontend/Dockerfile" ]; then
    if grep -q "FROM node:18" frontend/Dockerfile; then
        echo -e "${GREEN}✓${NC} Node 18 base image"
    else
        echo -e "${RED}✗${NC} Node 18 base image not found"
        ((ERRORS++))
    fi
    
    if grep -q "EXPOSE 3000" frontend/Dockerfile; then
        echo -e "${GREEN}✓${NC} Port 3000 exposed"
    else
        echo -e "${YELLOW}⚠${NC} Port 3000 not explicitly exposed"
    fi
fi
echo ""

echo -e "${YELLOW}Validating Nginx configuration...${NC}"
if [ -f "docker/nginx/default.conf" ]; then
    if grep -q "fastcgi_pass" docker/nginx/default.conf; then
        echo -e "${GREEN}✓${NC} PHP-FPM proxy configuration found"
    else
        echo -e "${RED}✗${NC} PHP-FPM proxy configuration missing"
        ((ERRORS++))
    fi
    
    if grep -q "gzip" docker/nginx/default.conf; then
        echo -e "${GREEN}✓${NC} Gzip compression enabled"
    else
        echo -e "${YELLOW}⚠${NC} Gzip compression not found"
    fi
    
    if grep -q "X-Frame-Options" docker/nginx/default.conf; then
        echo -e "${GREEN}✓${NC} Security headers configured"
    else
        echo -e "${YELLOW}⚠${NC} Security headers not found"
    fi
fi
echo ""

echo -e "${YELLOW}Validating environment configuration...${NC}"
if [ -f ".env.example" ]; then
    if grep -q "DB_DATABASE" .env.example; then
        echo -e "${GREEN}✓${NC} Database configuration present"
    else
        echo -e "${RED}✗${NC} Database configuration missing"
        ((ERRORS++))
    fi
    
    if grep -q "NGINX_PORT" .env.example; then
        echo -e "${GREEN}✓${NC} Port configuration present"
    else
        echo -e "${YELLOW}⚠${NC} Port configuration not found"
    fi
fi
echo ""

echo -e "${YELLOW}Checking Docker daemon...${NC}"
if command -v docker &> /dev/null; then
    echo -e "${GREEN}✓${NC} Docker is installed"
    DOCKER_VERSION=$(docker --version)
    echo -e "  ${BLUE}${DOCKER_VERSION}${NC}"
else
    echo -e "${RED}✗${NC} Docker is not installed"
    ((ERRORS++))
fi

if command -v docker-compose &> /dev/null; then
    echo -e "${GREEN}✓${NC} Docker Compose is installed"
    COMPOSE_VERSION=$(docker-compose --version)
    echo -e "  ${BLUE}${COMPOSE_VERSION}${NC}"
elif docker compose version &> /dev/null; then
    echo -e "${GREEN}✓${NC} Docker Compose (v2) is installed"
    COMPOSE_VERSION=$(docker compose version)
    echo -e "  ${BLUE}${COMPOSE_VERSION}${NC}"
else
    echo -e "${RED}✗${NC} Docker Compose is not installed"
    ((ERRORS++))
fi
echo ""

echo -e "${YELLOW}Validating docker-compose syntax...${NC}"
if docker-compose config > /dev/null 2>&1 || docker compose config > /dev/null 2>&1; then
    echo -e "${GREEN}✓${NC} docker-compose.yml syntax is valid"
else
    echo -e "${RED}✗${NC} docker-compose.yml has syntax errors"
    ((ERRORS++))
fi
echo ""

echo -e "${BLUE}════════════════════════════════════════════════${NC}"
if [ $ERRORS -eq 0 ]; then
    echo -e "${GREEN}✓ All checks passed! Infrastructure is ready.${NC}"
    echo ""
    echo -e "${YELLOW}Next steps:${NC}"
    echo -e "  1. Run ${BLUE}./setup.sh${NC} to initialize the environment"
    echo -e "  2. Or use ${BLUE}make setup${NC} for automated setup"
    echo -e "  3. Or manually: ${BLUE}docker-compose up -d --build${NC}"
    exit 0
else
    echo -e "${RED}✗ Found $ERRORS error(s). Please fix them before proceeding.${NC}"
    exit 1
fi
