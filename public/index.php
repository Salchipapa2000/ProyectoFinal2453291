<?php

$conexion = @new mysqli(
    "127.0.0.1",
    "root",
    "",
    "manos_de_chichi",
    3307
);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

echo "¡CONEXIÓN EXITOSA!";

$conexion->close();