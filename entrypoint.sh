#!/bin/sh

# Garante que as pastas cruciais do Adianti existam e tenham as permissões corretas
# (Caso o SQLite esteja em outro caminho, mude 'app/database' para o caminho correto)
# mkdir -p app/database app/tmp app/output app/logs

# Ajusta o dono das pastas para o usuário www-data (que internamente é o UID 1002)
chown -R www-data:www-data app/database app/tmp app/output app/logs
chmod -R 775 app/database app/tmp app/output app/logs

# Executa o comando principal do container (que é o php-fpm)
exec "$@"