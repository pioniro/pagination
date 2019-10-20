FROM phpdockerio/php73-cli

ARG DEBIAN_FRONTEND=noninteractive
RUN apt-get update \
    && apt-get -y --no-install-recommends install \
    locales \
    wget \
    php7.3-pgsql \
    php7.3-dev \
    php7.3-xml \
    php-pear \
    build-essential \
    && pecl install xdebug \
    && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

RUN wget -q https://getcomposer.org/installer --no-check-certificate \
    && php installer \
    && ln -s /composer.phar /usr/bin/composer

RUN echo 'zend_extension=/usr/lib/php/20180731/xdebug.so' > /etc/php/7.3/cli/conf.d/20-xdebug.ini

RUN usermod -u 1000 www-data \
    && groupmod -g 1000 www-data \
    && mkdir /var/www && chown www-data:www-data /var/www
