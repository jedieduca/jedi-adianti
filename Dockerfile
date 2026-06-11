# Imagem base oficial do PHP 8.3 FPM (Baseada em Debian)
FROM php:8.3-fpm

# Definição dos argumentos de UID e GID
ARG UID=1002
ARG GID=1005

# Instala o pacote 'passwd' e as dependências com travas de segurança contra travamentos
RUN DEBIAN_FRONTEND=noninteractive apt-get update && apt-get install -y --no-install-recommends \
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

# Altera o UID e GID do www-data existente no Debian para os seus IDs (1002 e 1005)
# E adiciona o www-data ao grupo root para corrigir a permissão de escrita em /proc/self/fd/2
RUN groupmod -g ${GID} www-data \
    && usermod -u ${UID} -g ${GID} -G root www-data

# Define o diretório de trabalho padrão
WORKDIR /var/www/html

# Cria as pastas que o Adianti exige ANTES de mapear o volume, garantindo que herdem as permissões
RUN mkdir -p app/database app/tmp app/output app/logs \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html

# Garante que o PHP-FPM escute na porta 9000 globalmente
RUN sed -i 's/listen = 127.0.0.1:9000/listen = 0.0.0.0:9000/g' /usr/local/etc/php-fpm.d/www.conf

# Define o usuário que executará o PHP-FPM internamente de forma nativa e segura
USER www-data

EXPOSE 9000

CMD ["php-fpm"]