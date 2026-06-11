# Imagem base PHP 8.3 FPM com Alpine
FROM php:8.3-fpm

# ==============================
# 🔹 UID/GID (ADICIONE AQUI)
# ==============================
# ARG UID=1002
# ARG GID=1005

# RUN groupmod -g ${GID} www-data \
#     && usermod -u ${UID} -g ${GID} www-data

# Instala as extensões PHP essenciais (ex: MySQL/Postgres, GD para imagens)
# A ordem aqui é importante:
# - apk add: Instala dependências do sistema operacional
# - docker-php-ext-install: Compila as extensões PHP

# Dependências básicas
RUN apt-get update && apt-get install -y \
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
    && apt-get clean
#    && rm -rf /var/lib/apt/lists/*


# Define o diretório de trabalho onde seu código PHP estará
# Se o seu código PHP estiver em uma subpasta, ajuste o WORKDIR
WORKDIR /var/www/html

# 4. Copia o arquivo de configuração do FPM (opcional, mas recomendado)
# Use um arquivo .conf personalizado se precisar ajustar pool de workers, etc.
# COPY ./docker/php/www.conf /usr/local/etc/php-fpm.d/www.conf

USER www-data

# Copia as aplicações
#COPY ./cadJEDI/adm ./adm
#COPY ./cadJEDI/mad ./mad


# 5. Expõe a porta padrão do FPM (9000)
EXPOSE 9000

# O comando padrão (CMD) da imagem php:8.3-fpm-alpine já é para iniciar o PHP-FPM,
# então não precisamos redefini-lo aqui.