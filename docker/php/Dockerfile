FROM php:7.4-apache AS nextouch_base

# Install packages
RUN apt-get update && apt-get install -y \
  git \
  gzip \
  imagemagick \
  jpegoptim \
  libbz2-dev \
  libcurl4-openssl-dev \
  libfreetype6-dev \
  libicu-dev \
  libjpeg62-turbo-dev \
  libmcrypt-dev \
  libonig-dev \
  libpng-dev \
  libsodium-dev \
  libssh2-1-dev \
  libxslt1-dev \
  libzip-dev \
  lsof \
  optipng \
  procps \
  vim \
  zip \
  && apt-get clean

# Configure php extensions
RUN docker-php-ext-configure \
  gd --with-jpeg=/usr/include/ --with-freetype=/usr/include/

# Install php extensions and apache modules
RUN docker-php-ext-install \
  bcmath \
  bz2 \
  calendar \
  exif \
  gd \
  gettext \
  intl \
  mbstring \
  mysqli \
  opcache \
  pcntl \
  pdo_mysql \
  soap \
  sockets \
  sodium \
  sysvmsg \
  sysvsem \
  sysvshm \
  xsl \
  zip

RUN pecl channel-update pecl.php.net \
  && pecl install xdebug && docker-php-ext-enable xdebug \
  && pecl install apcu && docker-php-ext-enable apcu \
  && a2enmod rewrite

# Add group & user
RUN groupadd -g 1000 magento \
  && useradd -g 1000 -u 1000 -d /var/www -s /bin/bash magento

# Install node
RUN apt-get install -y gnupg \
  && curl -sL https://deb.nodesource.com/setup_14.x | bash - \
  && apt-get install -y nodejs \
  && mkdir /var/www/.config /var/www/.npm \
  && chown magento:magento /var/www/.config /var/www/.npm \
  && npm install -g grunt-cli

# Install composer
RUN curl -sS https://getcomposer.org/installer | \
  php -- --2 --install-dir=/usr/local/bin --filename=composer

USER magento:magento

VOLUME /var/www

WORKDIR /var/www/html

EXPOSE 9001

FROM nextouch_base AS nextouch_cron

USER root

RUN apt-get update && apt-get install -y cron && apt-get clean

WORKDIR /app

COPY crontab .
COPY cron.sh .

CMD ["sh", "/app/cron.sh"]
