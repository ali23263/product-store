# Use Node.js 18 Alpine for smaller image size
FROM node:18-alpine

# Set build arguments for user/group IDs
ARG USER_ID=1000
ARG GROUP_ID=1000

# Install system dependencies
RUN apk add --no-cache \
    bash \
    git \
    curl \
    && rm -rf /var/cache/apk/*

# Set working directory
WORKDIR /app

# Create application user with matching host UID/GID
RUN if [ "$USER_ID" != "1000" ] || [ "$GROUP_ID" != "1000" ]; then \
    deluser --remove-home node 2>/dev/null || true; \
    addgroup -g ${GROUP_ID} -S appgroup; \
    adduser -u ${USER_ID} -S appuser -G appgroup; \
    else \
    adduser -u 1000 -D -s /bin/sh appuser || true; \
    fi

# Copy package files for better layer caching
COPY --chown=${USER_ID}:${GROUP_ID} package*.json ./

# Install dependencies
RUN npm ci --prefer-offline --no-audit

# Copy application files
COPY --chown=${USER_ID}:${GROUP_ID} . .

# Change ownership of the app directory
RUN chown -R ${USER_ID}:${GROUP_ID} /app

# Expose Vite dev server port
EXPOSE 3000

# Health check
HEALTHCHECK --interval=30s --timeout=3s --retries=3 \
    CMD curl -f http://localhost:3000/ || exit 1

# Switch to application user
USER ${USER_ID}:${GROUP_ID}

# Default command (can be overridden in docker-compose)
CMD ["npm", "run", "dev"]
