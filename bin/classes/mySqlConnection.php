<?php
require_once dirname(__DIR__) . '/config.php';
class mySqlConnection
{
    public function newConnection(): PDO
    {
        $config = database_options();
        try {
            return new PDO($config['dsn'], $config['user'], $config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $error) {
            throw new RuntimeException('Banco de dados indisponível. Verifique a configuração privada.');
        }
    }
}
