<?php
declare(strict_types=1);

namespace App\Config;

use PDO;
use PDOException;

/** Gerencia uma única conexão PDO para toda a aplicação. */
final class Database
{
    private static ?PDO $connection = null;

    public static function connection(): PDO
    {
        if (self::$connection instanceof PDO) {
            return self::$connection;
        }

        $config = require dirname(__DIR__, 2) . '/config/database.php';
        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=%s',
            $config['host'],
            $config['port'],
            $config['database'],
            $config['charset']
        );

        try {
            self::$connection = new PDO($dsn, $config['username'], $config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $exception) {
            // A mensagem técnica fica nos logs; a API devolve uma mensagem segura.
            error_log('Falha na conexão com MySQL: ' . $exception->getMessage());
            throw new \RuntimeException('Não foi possível conectar ao banco de dados. Confira a configuração e se o MySQL está iniciado.');
        }

        return self::$connection;
    }
}
