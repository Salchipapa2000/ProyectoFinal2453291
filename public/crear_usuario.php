<?php

require_once __DIR__ . '/../app/config/database.php';

try {

    $baseDatos = new Database();
    $conexion = $baseDatos->conectar();

    $nombre = "Administrador";
    $usuario = "admin";
    $password = password_hash(
        "Admin123!",
        PASSWORD_DEFAULT
    );

    $rol = "administrador";

    $sql = "INSERT INTO usuarios
            (nombre, usuario, password, rol)
            VALUES (?, ?, ?, ?)";

    $consulta = $conexion->prepare($sql);

    $consulta->bind_param(
        "ssss",
        $nombre,
        $usuario,
        $password,
        $rol
    );

    $consulta->execute();

    echo "Usuario administrador creado correctamente.";

    $consulta->close();
    $conexion->close();

} catch (Throwable $e) {

    echo "No se pudo crear el usuario. Comprueba si ya existe.";
}
