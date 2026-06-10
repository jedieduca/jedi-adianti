#!/bin/sh

# Cria as pastas do Adianti caso elas não existam no volume espelhado
# mkdir -p app/database app/tmp app/output app/logs

# Aplica as permissões corretas para o proprietário www-data
chown -R www-data:www-data app/database app/tmp app/output app/logs
chmod -R 775 app/database app/tmp app/output app/logs

# Transfere a execução para o usuário www-data de forma segura e roda o php-fpm
exec gosu www-data "$@"