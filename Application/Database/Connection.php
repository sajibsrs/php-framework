<?php

namespace Application\Database;

use PDO;
use Exception;
use PDOException;

class Connection
{
    const ERROR_CONFIG = 'ERROR: Database configuration error';

    public $pdo;

    public function __construct(array $config)
    {
        if (! isset($config) || empty($config)) {
            $error = __METHOD__ . ' : ' . self::ERROR_CONFIG . PHP_EOL;
            throw new Exception($error);
        }

        $dsn = $config['driver'] . ':' . 'host=' . $config['host'] . ';' . 'dbname=' . $config['dbname'];

        try {
            $this->pdo = new PDO(
                $dsn,
                $config['user'],
                $config['password']
            );
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}
