#!/bin/sh

# 1. Cria as pastas do Adianti caso elas não existam no volume
# mkdir -p app/database app/tmp app/output app/logs

# 2. Aplica as permissões estritas para o proprietário www-data
chown -R www-data:www-data app/database app/tmp app/output app/logs
chmod -R 775 app/database app/tmp app/output app/logs

# 3. CORREÇÃO DE LOG: Garante que o www-data consiga gravar nos logs globais do PHP se necessário
chown -R www-data:www-data /var/log/

# 4. Executa o PHP-FPM forçando-o a rodar em primeiro plano (-F) para o container não fechar
if [ "$1" = 'php-fpm' ]; then
    exec gosu www-data php-fpm -F
fi

# Caso tenha passado outro comando customizado
exec gosu www-data "$@"