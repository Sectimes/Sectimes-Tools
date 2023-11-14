FROM php:7.4-fpm

# Arguments defined in docker-compose.yml
ARG user
ARG uid

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    wget \
    curl \
    net-tools \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    iputils-ping \
    zip \
    unzip \
    libc6 \
    ffuf

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN composer require guzzlehttp/guzzle

# Install golang
# RUN wget https://go.dev/dl/go1.21.4.linux-amd64.tar.gz
# RUN tar -C /usr/local/ -xzf go1.21.4.linux-amd64.tar.gz
# ENV PATH="${PATH}:/usr/local/go/bin"
# ENV CGO_ENABLED=1
# ENV GOOS=linux
# ENV GOARCH=amd64

# Install ffuf
# RUN go install github.com/ffuf/ffuf/v2@latest

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

COPY . /var/www

# Set working directory
WORKDIR /var/www


USER $user