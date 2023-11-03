<?php

namespace App;

use PDO;

class Db
{
    /**
     * Creates new connection to database based on env vars
     *
     * @return PDO
     * @throws \PDOException if connection fails
     */
    public static function getConnection(): PDO
    {
        [
            'DB_HOST'     => $host,
            'DB_PORT'     => $port,
            'DB_NAME'     => $dbname,
            'DB_CHARSET'  => $charset,
            'DB_USER'     => $user,
            'DB_PASSWORD' => $password
        ] = $_ENV;

        $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=$charset";
        return new PDO($dsn, $user, $password, [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }
}
