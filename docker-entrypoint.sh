#!/bin/sh
set -e

# Garante que as pastas de escrita do Adianti existam e pertençam ao www-data
# (o volume ./:/var/www/html sobrescreve qualquer ajuste feito no build)
for d in mad/app/output mad/tmp adm/app/output adm/tmp; do
    mkdir -p "/var/www/html/$d"
    chown -R www-data:www-data "/var/www/html/$d"
    chmod -R 775 "/var/www/html/$d"
done

# Continua com o entrypoint padrão da imagem oficial (inicia o php-fpm)
exec docker-php-entrypoint "$@"
