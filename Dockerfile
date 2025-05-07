FROM php:8.2-fpm-alpine

# Install NGINX, Git, Bash, MariaDB dev (needed for mysqli), and Supervisor
RUN apk add --no-cache \
    nginx \
    git \
    bash \
    mariadb-dev \
    supervisor \
    && docker-php-ext-install mysqli

# Create required directories
RUN mkdir -p /run/nginx

# Set working directory
WORKDIR /app

# Copy your application code
COPY . .

# Copy NGINX config
COPY nginx.conf /etc/nginx/nginx.conf

# Copy supervisord config
COPY supervisord.conf /etc/supervisord.conf

# Expose the port Render will listen to
EXPOSE 10000

# Start Supervisor (manages both PHP-FPM and NGINX)
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
