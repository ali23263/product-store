# Use PHP 8.2 FPM Alpine for smaller image size
FROM php:8.2-fpm-alpine

# Set build arguments for user/group IDs
ARG USER_ID=1000
ARG GROUP_ID=1000

# Install system dependencies
RUN apk add --no-cache \
    bash \
    curl \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    postgresql-dev \
    icu-dev \
    libxml2-dev \
    linux-headers \
    fcgi \
    && rm -rf /var/cache/apk/*

# Configure and install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_pgsql \
    pgsql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    intl \
    opcache \
    xml \
    soap

# Install Redis extension
RUN apk add --no-cache --virtual .build-deps \
    autoconf \
    g++ \
    make \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del .build-deps

# Install Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Configure PHP-FPM and PHP settings
RUN { \
    echo '[www]'; \
    echo 'listen = 9000'; \
    echo 'listen.owner = www-data'; \
    echo 'listen.group = www-data'; \
    echo 'pm = dynamic'; \
    echo 'pm.max_children = 20'; \
    echo 'pm.start_servers = 5'; \
    echo 'pm.min_spare_servers = 3'; \
    echo 'pm.max_spare_servers = 10'; \
    echo 'pm.max_requests = 500'; \
    echo 'pm.status_path = /status'; \
    echo 'ping.path = /ping'; \
    echo 'clear_env = no'; \
    echo 'catch_workers_output = yes'; \
} > /usr/local/etc/php-fpm.d/zz-docker.conf

# Copy custom PHP configuration
RUN { \
    echo 'memory_limit=${PHP_MEMORY_LIMIT}'; \
    echo 'upload_max_filesize=${PHP_UPLOAD_MAX_FILESIZE}'; \
    echo 'post_max_size=${PHP_POST_MAX_SIZE}'; \
    echo 'max_execution_time=300'; \
    echo 'max_input_time=300'; \
    echo 'date.timezone=UTC'; \
    echo 'expose_php=Off'; \
    echo 'display_errors=On'; \
    echo 'log_errors=On'; \
    echo 'error_reporting=E_ALL'; \
} > /usr/local/etc/php/conf.d/custom.ini

# OpCache configuration for better performance
RUN { \
    echo 'opcache.enable=1'; \
    echo 'opcache.memory_consumption=256'; \
    echo 'opcache.interned_strings_buffer=16'; \
    echo 'opcache.max_accelerated_files=10000'; \
    echo 'opcache.revalidate_freq=2'; \
    echo 'opcache.fast_shutdown=1'; \
    echo 'opcache.validate_timestamps=1'; \
} > /usr/local/etc/php/conf.d/opcache.ini

# Create healthcheck script for PHP-FPM
RUN echo '#!/bin/sh' > /usr/local/bin/php-fpm-healthcheck \
    && echo 'SCRIPT_NAME=/ping SCRIPT_FILENAME=/ping REQUEST_METHOD=GET cgi-fcgi -bind -connect 127.0.0.1:9000' >> /usr/local/bin/php-fpm-healthcheck \
    && chmod +x /usr/local/bin/php-fpm-healthcheck

# Set working directory
WORKDIR /var/www/html

# Create application user with matching host UID/GID
RUN if [ "$USER_ID" != "82" ]; then \
    deluser --remove-home www-data 2>/dev/null || true; \
    addgroup -g ${GROUP_ID} -S appgroup; \
    adduser -u ${USER_ID} -S appuser -G appgroup; \
    else \
    adduser -u 1000 -S appuser -G www-data; \
    fi

# Create necessary directories and set permissions
RUN mkdir -p \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    && chown -R ${USER_ID}:${GROUP_ID} /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# Copy composer files first for better layer caching
COPY --chown=${USER_ID}:${GROUP_ID} composer*.json ./

# Install dependencies as appuser (will be done when mounting volume)
# This layer will be cached if composer files don't change

# Expose PHP-FPM port
EXPOSE 9000

# Health check
HEALTHCHECK --interval=30s --timeout=3s --retries=3 \
    CMD php-fpm-healthcheck || exit 1

# Switch to application user
USER ${USER_ID}:${GROUP_ID}

# Start PHP-FPM
CMD ["php-fpm"]
