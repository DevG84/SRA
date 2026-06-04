<?php

$env = parse_ini_file(__DIR__ . '/../.env');
class Connection {
    private $host;
    private $nombreBD;
    private $usuario;
    private $password;
    private $puerto;

    public function __construct() {
        global $env;
        $this->host = $env['DB_HOST'];
        $this->nombreBD = $env['DB_NAME'];
        $this->usuario = $env['DB_USER'];
        $this->password = $env['DB_PASS'];
        $this->puerto = $env['DB_PORT'];
    }

    public function connect() {
        try {
            $connection = new PDO(
                "mysql:host=$this->host;dbname=$this->nombreBD;port=$this->puerto;charset=utf8mb4",
                $this->usuario,
                $this->password
            );
            $connection->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );
            return $connection;
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }
}