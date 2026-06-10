#!/bin/sh

# 1. Cria as pastas do Adianti caso elas não existam no volume
# mkdir -p app/database app/tmp app/output app/logs

# 2. Aplica as permissões estritas para o proprietário www-data
chown -R www-data:www-data app/database app/tmp app/output app/logs
chmod -R 775 app/database app/tmp app/output app/logs

# 3. Garante que o www-data consiga gravar nos logs globais do PHP se necessário
chown -R www-data:www-data /var/log/

# 4. Executa o PHP-FPM usando o caminho absoluto (/usr/local/sbin/php-fpm)
if [ "$1" = 'php-fpm' ]; then
    exec gosu www-data /usr/local/sbin/php-fpm -F
fi

# Caso venha outro comando customizado, tenta rodar com o caminho absoluto também se for php-fpm
if [ "$1" = '/usr/local/sbin/php-fpm' ]; then
    exec gosu www-data /usr/local/sbin/php-fpm -F
fi

exec gosu www-data "$@"