<?php
declare(strict_types=1);

namespace Metricas\Database;

use Metricas\Config\Config;
use PDO;

final class ConnectionFactory
{
    public static function create(Config $config): PDO
    {
        $dsn = "mysql:host={$config->dbHost};port={$config->dbPort};dbname={$config->dbName};charset={$config->dbCharset}";

        return new PDO($dsn, $config->dbUser, $config->dbPass, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    }
}
