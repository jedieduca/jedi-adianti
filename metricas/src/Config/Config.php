<?php
declare(strict_types=1);

namespace Metricas\Config;

final class Config
{
    public readonly string $dbHost;
    public readonly string $dbPort;
    public readonly string $dbName;
    public readonly string $dbUser;
    public readonly string $dbPass;
    public readonly string $dbCharset;
    public readonly string $apiBaseUrl;
    public readonly string $apiUsuario;
    public readonly string $apiSenha;

    public function __construct()
    {
        // Todos os valores podem ser sobrescritos via variável de ambiente do
        // container (docker-compose.yml); os literais abaixo são o fallback
        // usado hoje no ambiente de dev.
        $this->dbHost     = getenv('JEDI_DB_HOST') ?: 'mariadb_db';
        $this->dbPort     = getenv('JEDI_DB_PORT') ?: '3306';
        $this->dbName     = getenv('JEDI_DB_NAME') ?: 'jedi-educa-v2';
        $this->dbUser     = getenv('JEDI_DB_USER') ?: 'root';
        $this->dbPass     = getenv('JEDI_DB_PASS') ?: 'mys2Edu4Up@2025';
        $this->dbCharset  = getenv('JEDI_DB_CHARSET') ?: 'utf8mb4';
        $this->apiBaseUrl = getenv('JEDI_API_BASE_URL') ?: 'http://api.dev.jedieduca.com.br';
        $this->apiUsuario = getenv('JEDI_API_USUARIO') ?: 'admin@jedieduca.com.br';
        $this->apiSenha   = getenv('JEDI_API_SENHA') ?: 'JediEduc@2026';
    }
}
