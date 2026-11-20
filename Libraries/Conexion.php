<?php

class Conexion
{
    private static ?PDO $connection = null;

    public function __construct()
    {
        // Una sola conexión PDO compartida por todos los modelos de la petición.
        if (self::$connection === null) {
            $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            self::$connection = new PDO($dsn, DB_USER, DB_PASS);
            self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }
    }

    public function connect(): PDO
    {
        return self::$connection;
    }
}
