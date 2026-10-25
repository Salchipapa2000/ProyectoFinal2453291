<?php

class Database
{
    private string $host = "127.0.0.1";
    private int $port = 3307;
    private string $db_name = "manos_de_chichi";
    private string $username = "root";
    private string $password = "";

    public function conectar(): PDO
    {
        try {
            $conexion = new PDO(
                "mysql:host={$this->host};port={$this->port};dbname={$this->db_name};charset=utf8mb4",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );

            return $conexion;

       } catch (PDOException $e) {
            throw $e;
        }
    }
}
