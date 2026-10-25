<?php

class Database
{
    private string $host = "127.0.0.1";
    private int $port = 3307;
    private string $username = "root";
    private string $password = "";
    private string $database = "manos_de_chichi";

    public function conectar(): mysqli
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        $conexion = new mysqli(
            $this->host,
            $this->username,
            $this->password,
            $this->database,
            $this->port
        );

        $conexion->set_charset("utf8mb4");

        return $conexion;
    }
}