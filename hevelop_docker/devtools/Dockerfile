FROM hevelop/php-devtools:7.4-1.1.1

RUN echo "memory_limit=3G" >> /usr/local/etc/php/conf.d/zz-php.ini

# Install Xdebug (but don't enable)
#RUN pecl install -o -f xdebug-2.8.1
#
#RUN echo zend_extension=/usr/local/lib/php/extensions/no-debug-non-zts-20151012/xdebug.so >> /usr/local/etc/php/conf.d/zz-php.ini


CMD ["php", "-A"]
