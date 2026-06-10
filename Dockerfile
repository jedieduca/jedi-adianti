# Imagem base oficial do PHP 8.3 FPM (Baseada em Debian)
FROM php:8.3-fpm

# Definição dos argumentos de UID e GID
ARG UID=1002
ARG GID=1005

# 1. Instala as dependências do sistema e ferramentas necessárias (incluindo o gosu)
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
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        pdo_pgsql \
        soap \
        sockets \
        exif \
        intl \
        opcache \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# 2. Modifica o UID e GID do www-data que JÁ EXISTE no Debian para os valores desejados
# Removemos travas de cache modificando diretamente o arquivo do sistema se necessário,
# mas o usermod clássico resolve se feito antes de alternar contextos.
RUN groupmod -g ${GID} www-data \
    && usermod -u ${UID} -g ${GID} www-data

# Define o diretório de trabalho
WORKDIR /var/www/html

# 3. Copia e configura o script de inicialização
COPY ./entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# O container inicia como root para que o entrypoint possa corrigir as permissões das pastas
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

EXPOSE 9000

CMD ["php-fpm"]