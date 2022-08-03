FROM hevelop/php:7.4-fpm-buster

ARG UNAME=www-data
ARG UID=1000
ARG GID=1000

RUN if grep -q $GID /etc/group; then GROUP_NAME=$(grep :$GID: /etc/group | sed -En "s/(.*):x:$GID:/\1/p") && delgroup $GROUP_NAME; fi;
RUN if grep -q $UNAME /etc/group; then groupmod -g $GID $UNAME; else groupadd -g $GID $UNAME; fi;

RUN usermod -u $UID $UNAME

# Instaellanll Xdebug (but don't enable)
RUN pecl install -o -f xdebug-2.9.8

# Install netstat for dynamic xdebug remote_host from entrypoint.sh
RUN apt-get  --allow-releaseinfo-change update \
    && apt-get install -y net-tools libwebp-dev optipng gifsicle jpegoptim libpng-dev webp

RUN docker-php-ext-install opcache
COPY ./conf.d/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
#COPY ./entrypoint.sh /entrypoint.sh
#RUN chmod +x /entrypoint.sh
#ENTRYPOINT ["/entrypoint.sh"]

WORKDIR /var/www/deploy

CMD ["php-fpm", "-F", "-R"]
