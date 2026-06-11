# Imagem base oficial do PHP 8.3 FPM (Baseada em Debian)
FROM php:8.3-fpm

# Definição dos argumentos de UID e GID
ARG UID=1002
ARG GID=1005

# Instala o pacote 'passwd' (que fornece groupmod/usermod no Debian) e as dependências das extensões
RUN apt-get update && apt-get install -y \
    passwd \
    libxml2-dev \
    libicu-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libpq-dev \
    imagemagick \
    libmagickwand-dev \
    ghostscript \
    zip unzip \
    && pecl install imagick \
    && docker-php-ext-enable imagick \
    \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        pdo_pgsql \
        soap \
        sockets \
        exif \
        intl \
        opcache \
    \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Agora os comandos groupmod e usermod vão funcionar perfeitamente
RUN groupmod -g ${GID} www-data \
    && usermod -u ${UID} -g ${GID} www-data

# Define o diretório de trabalho
WORKDIR /var/www/html

# Define o usuário padrão para os comandos internos
USER www-data 

EXPOSE 9000

CMD ["php-fpm"]