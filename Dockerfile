FROM php:7.4-fpm

# Arguments defined in docker-compose.yml
ARG user
ARG uid

# Install system dependencies
RUN apt-get update && apt-get install -y \
    dnsutils \
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
    ffuf \
    nodejs \
    npm \
    nmap

# Install subfinder
# https://github.com/projectdiscovery/subfinder
RUN wget -P /var/www/subfinder https://github.com/projectdiscovery/subfinder/releases/download/v2.6.3/subfinder_2.6.3_linux_arm64.zip
RUN cd /var/www/subfinder && unzip subfinder_2.6.3_linux_arm64.zip && mv subfinder /usr/bin/

# This is a risk: Give nmap Setuid permission
RUN chmod u+s /usr/bin/nmap

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

# Install Webanalyze tools
# https://github.com/rverton/webanalyze?tab=readme-ov-file
RUN wget -P /var/www/webanalyze https://github.com/rverton/webanalyze/releases/download/v0.4.1/webanalyze_Linux_arm64.tar.gz
RUN cd /var/www/webanalyze && tar -xvzf webanalyze_Linux_arm64.tar.gz
RUN chmod +x /var/www/webanalyze/webanalyze

# Install stacks-cli tools
# https://github.com/WeiChiaChang/stacks-cli
RUN npm install stacks-cli -g

# This command quite dangerous, since it can be easily exploited to privilege escalation
RUN chmod +s /usr/bin/nmap

RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

COPY . /var/www

RUN chmod +x /var/www/start.sh

# Set working directory
WORKDIR /var/www


USER $user