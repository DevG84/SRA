<?php
class Connection {
    private $host = "localhost";
    private $nombreBD = "sra_ets";
    private $usuario = "root";
    private $contraseña = "";
    private $puerto = "3306";

    public function connect() {
        try {
            $connection = new PDO(
                "mysql:host=$this->host;dbname=$this->nombreBD;port=$this->puerto;charset=utf8mb4",
                $this->usuario,
                $this->contraseña
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