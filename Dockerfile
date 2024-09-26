# Use the official PHP image as the base image
FROM php:8.1-apache

# Set the working directory inside the container
WORKDIR /var/www/html

# Copy the current directory contents into the container
COPY . /var/www/html

RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libzip-dev \
    zip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && docker-php-ext-install zip \
    && docker-php-ext-install pdo pdo_mysql


# Enable Apache mod_rewrite for URL rewriting
RUN a2enmod rewrite

# Set proper permissions for the web root
RUN chown -R www-data:www-data /var/www/html

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install project dependencies with Composer
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer self-update --2
RUN composer install
RUN composer dump-autoload

# Install cron
RUN apt-get update && apt-get -y install cron

# Copy the crontab file into the cron directory
COPY crontab /etc/cron.d/alko-cron

# Set the permissions for the cron job file
RUN chmod 0644 /etc/cron.d/alko-cron

# Apply the cron job
RUN crontab /etc/cron.d/alko-cron

# Ensure cron is running in the background
RUN touch /var/log/cron.log

# Expose the port Apache will run on
EXPOSE 80

# Start Apache server
CMD cron && apache2-foreground
