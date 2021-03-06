FROM php:7.2-cli
MAINTAINER Meritoo <github@meritoo.pl>

#
# Tools & libraries
#
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        vim \
        git \
        unzip \
        libicu-dev \
        locales \
    && apt-get clean \
    && rm -rf \
        /var/lib/apt/lists/* \
        /tmp/* \
        /var/tmp/*

#
# Generating locales:
# - de_DE
# - es_ES
# - en_GB
# - en_US
# - fr_FR
# - it_IT
# - pl_PL
# - ru_RU
#
RUN sed -i 's/^# de_DE/de_DE/g; \
            s/^# es_ES/es_ES/g; \
            s/^# en_GB/en_GB/g; \
            s/^# en_US/en_US/g; \
            s/^# fr_FR/fr_FR/g; \
            s/^# it_IT/it_IT/g; \
            s/^# pl_PL/pl_PL/g; \
            s/^# ru_RU/ru_RU/g;' /etc/locale.gen \
    && locale-gen

#
# Set default language
#
# Required to avoid problem with using strange language by error messages.
# Example: "chmod(): Aucun fichier ou dossier de ce type"
#
ENV LANGUAGE=en_US.UTF-8

#
# PHP extensions
#
RUN docker-php-ext-install \
    intl \
    mbstring

#
# PHP extensions (PECL):
# - Xdebug
#
RUN pecl install \
        xdebug \
    && docker-php-ext-enable \
        xdebug

COPY xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

#
# PHP configuration:
# - default configuration
# - timezone
#
COPY php.ini /usr/local/etc/php/php.ini
ARG TIMEZONE
RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime \
    && echo ${TIMEZONE} > /etc/timezone \
    && printf '[PHP]\ndate.timezone = "%s"\n' ${TIMEZONE} > /usr/local/etc/php/conf.d/tzone.ini \
    && "date"
#RUN echo "\n""date.timezone = $TIMEZONE""\n" >> /usr/local/etc/php/php.ini

#
# Phing
#
RUN pear channel-discover pear.phing.info \
    && pear install [--alldeps] phing/phing

#
# Composer - environment variables:
# - disable warning about running commands as root/super user
# - disable automatic clearing of sudo sessions
#
# More:
# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
#
ENV COMPOSER_ALLOW_SUPERUSER 1

#
# Composer + https://packagist.org/packages/hirak/prestissimo package
#
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php -r "if (hash_file('SHA384', 'composer-setup.php') === \
        '48e3236262b34d30969dca3c37281b3b4bbe3221bda826ac6a9a62d6444cdb0dcd0615698a5cbe587c3f0fe57a54d8f5') { echo \
        'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');" \
    && composer global require \
        --no-plugins \
        --no-scripts \
        --no-progress \
        --no-suggest \
        --no-interaction \
        --prefer-dist \
        --optimize-autoloader \
        --classmap-authoritative \
        hirak/prestissimo \
    && rm -rf ~/.composer/cache/* \
    && composer clear-cache \
    && composer --version

#
# Bash
#
RUN sed -i 's/^# export/export/g; \
            s/^# alias/alias/g;' ~/.bashrc \
    && echo 'COLUMNS=200'"\n" >> ~/.bashrc

#
# Use project-related binaries globally
#
ENV PATH="/project/vendor/bin:${PATH}"

WORKDIR /project
