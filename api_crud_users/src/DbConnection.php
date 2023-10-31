<?php

namespace App;

use PDO;

class DbConnection
{
    /**
     * Gets a new PDO Connection
     *
     * @return PDO
     * @throws \PDOException if connection fails
     */
    public static function getConnection(): PDO
    {
        [
            'DB_HOST' => $host,
            'DB_PORT' => $port,
            'DB_NAME' => $name,
            'DB_CHARSET' => $charset,
            'DB_USER' => $user,
            'DB_PASSWORD' => $password
        ] = $_ENV;

        $dsn = "mysql:host=$host;port=$port;dbname=$name;charset=$charset";
        return new PDO($dsn, $user, $password);
    }
}
