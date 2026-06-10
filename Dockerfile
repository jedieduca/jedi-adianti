# Imagem base oficial do PHP 8.3 FPM (Baseada em Debian)
FROM php:8.3-fpm

# Definição dos argumentos de UID e GID
ARG UID=1002
ARG GID=1005

RUN apt-get update && apt-get install -y \
    passwd \
    gosu \
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
    && docker-php-ext-install -j$(nproc) pdo_mysql pdo_pgsql soap sockets exif intl opcache \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Agora modificamos o UID/GID do www-data existente no Debian para os seus IDs customizados
RUN groupmod -g ${GID} www-data \
    && usermod -u ${UID} -g ${GID} www-data

# Define o diretório de trabalho
WORKDIR /var/www/html

# Copia o script de inicialização para dentro do container
COPY ./entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# O container inicia como root para corrigir as permissões das pastas montadas por volumes
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

EXPOSE 9000

# O comando padrão que será passado para o entrypoint
CMD ["php-fpm"]