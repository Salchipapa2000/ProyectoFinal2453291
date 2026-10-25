<?php

require_once __DIR__ . '/../app/config/database.php';

$archivoControlador = __DIR__ . '/../app/controllers/ProductoController.php';

if (!file_exists($archivoControlador)) {
    die('ERROR: No se encuentra el archivo ProductoController.php en: '
        . htmlspecialchars($archivoControlador));
}

require_once $archivoControlador;

if (!class_exists('ProductoController')) {
    die('ERROR: El archivo existe, pero no se ha declarado la clase ProductoController. Revisa el nombre de la clase y el contenido del archivo.');
}

try {
    $baseDatos = new Database();
    $conexion = $baseDatos->conectar();

    $controlador = new ProductoController($conexion);
    $controlador->manejarSolicitud();

} catch (Throwable $e) {
    http_response_code(500);

    echo '<h2>Error de PHP</h2>';
    echo '<pre>' . htmlspecialchars($e->getMessage()) . '</pre>';
    echo '<p>Archivo: ' . htmlspecialchars($e->getFile()) . '</p>';
    echo '<p>Línea: ' . (int) $e->getLine() . '</p>';
}