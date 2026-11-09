<?php

session_start();

require_once __DIR__ . '/../app/config/csrf.php';

generarTokenCSRF();

require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/controllers/ProductoController.php';
require_once __DIR__ . '/../app/controllers/UsuarioController.php';
require_once __DIR__ . '/../app/controllers/VentaController.php';

try {

    $baseDatos = new Database();
    $conexion = $baseDatos->conectar();

    $usuarioController = new UsuarioController($conexion);

    $accion = $_GET['accion'] ?? 'inicio';

    // Acciones públicas.
    if ($accion === 'login') {
        $usuarioController->iniciarSesion();
        exit;
    }

    if ($accion === 'logout') {
        $usuarioController->cerrarSesion();
        exit;
    }

    // Proteger el resto del sistema.
    if (!isset($_SESSION['usuario_id'])) {
        header('Location: index.php?accion=login');
        exit;
    }

    // Restricción de acceso para administradores.
    $accionesAdministrativas = [
        'crear',
        'editar',
        'eliminar'
    ];

    if (
        in_array($accion, $accionesAdministrativas, true) &&
        $_SESSION['rol'] !== 'administrador'
    ) {
        http_response_code(403);
        echo "No tienes permisos para realizar esta acción.";
        exit;
    }

    // Mostrar el menú principal.
    if ($accion === 'inicio') {
        require __DIR__ . '/../app/views/inicio.php';
        exit;
    }

    // Enviar la solicitud al controlador correspondiente.
    $accionesVentas = [
        'ventas',
        'registrar_venta',
        'consultar_existencia',
        'liquidaciones'
    ];

    if (in_array($accion, $accionesVentas, true)) {
        $controlador = new VentaController($conexion);
    } else {
        $controlador = new ProductoController($conexion);
    }

    $controlador->manejarSolicitud();

} catch (Throwable $e) {

    http_response_code(500);
    error_log($e->getMessage());

    echo "Ocurrió un error al procesar la solicitud.";
}