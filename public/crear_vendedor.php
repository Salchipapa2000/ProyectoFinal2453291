<?php

require_once __DIR__ . '/../app/config/database.php';

try {
    $baseDatos = new Database();
    $conexion = $baseDatos->conectar();

    $nombre = "Vendedor de prueba";
    $usuario = "vendedor";
    $password = password_hash("Vendedor123!", PASSWORD_DEFAULT);
    $rol = "vendedor";

    $sql = "INSERT INTO usuarios (nombre, usuario, password, rol)
            VALUES (?, ?, ?, ?)";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssss", $nombre, $usuario, $password, $rol);
    $stmt->execute();

    echo "Usuario vendedor creado correctamente.";

    $stmt->close();
    $conexion->close();

} catch (Throwable $e) {
    echo "No se pudo crear el usuario. Puede que ya exista.";
}
