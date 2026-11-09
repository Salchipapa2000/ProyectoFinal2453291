<?php

require_once __DIR__ . '/../models/Venta.php';

class VentaController
{
    private Venta $modelo;

    public function __construct(mysqli $conexion)
    {
        $this->modelo = new Venta($conexion);
    }

    public function manejarSolicitud(): void
    {
        $accion = $_GET['accion'] ?? 'ventas';

        if ($accion === 'ventas') {
            $productos = $this->modelo->obtenerProductos();
            require __DIR__ . '/../views/ventas/index.php';
            return;
        }

        if ($accion === 'registrar_venta') {
            $this->registrar();
            return;
        }

        if ($accion === 'consultar_existencia') {
            $this->consultarExistencia();
            return;
        }

        http_response_code(404);
        echo "Acción no encontrada.";
    }

    private function registrar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            return;
        }

        $productos = $_POST['productos'] ?? [];

        if (!is_array($productos) || empty($productos)) {
            header('Location: index.php?accion=ventas&error=vacio');
            exit;
        }

        try {
            $this->modelo->registrar(
                (int) $_SESSION['usuario_id'],
                $productos
            );

            header('Location: index.php?accion=ventas&exito=1');
            exit;

        } catch (Throwable $e) {
            error_log($e->getMessage());
            header('Location: index.php?accion=ventas&error=venta');
            exit;
        }
    }

    private function consultarExistencia(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $cantidad = filter_input(INPUT_GET, 'cantidad', FILTER_VALIDATE_INT);

        if (!$id || !$cantidad || $cantidad < 1) {
            echo json_encode(['error' => 'Datos inválidos']);
            return;
        }

        $productos = $this->modelo->obtenerProductos();

        foreach ($productos as $producto) {
            if ((int) $producto['id'] === $id) {
                $restante = (int) $producto['existencia'] - $cantidad;

                echo json_encode([
                    'existencia' => (int) $producto['existencia'],
                    'restante' => $restante,
                    'minima' => (int) $producto['existencia_minima'],
                    'alerta' => $restante < (int) $producto['existencia_minima']
                ]);

                return;
            }
        }

        echo json_encode(['error' => 'Producto no encontrado']);
    }
}
