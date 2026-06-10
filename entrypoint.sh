#!/bin/sh

# Garante que as pastas cruciais do Adianti existam
# mkdir -p app/database app/tmp app/output app/logs

# Ajusta o proprietário para o www-data (UID 1002 / GID 1005)
chown -R www-data:www-data app/database app/tmp app/output app/logs
chmod -R 775 app/database app/tmp app/output app/logs

# Executa o comando principal (php-fpm) ALTERNANDO para o usuário www-data de forma segura
exec gosu www-data "$@"