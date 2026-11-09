<?php

class Usuario {

    private mysqli $conexion;

    public function __construct(mysqli $conexion) {
        $this->conexion = $conexion;
    }

    // Buscar un usuario por su nombre de usuario
    public function buscarPorUsuario(string $usuario): ?array {

        $sql = "SELECT id, nombre, usuario, password, rol, estado
                FROM usuarios
                WHERE usuario = ?";

        $consulta = $this->conexion->prepare($sql);
        $consulta->bind_param("s", $usuario);
        $consulta->execute();

        $resultado = $consulta->get_result();
        $datos = $resultado->fetch_assoc();

        $consulta->close();

        return $datos ?: null;
    }

}