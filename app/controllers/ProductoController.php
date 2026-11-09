<?php

require_once __DIR__ . '/../models/Producto.php';

class ProductoController
{
    private Producto $modelo;

    public function __construct(mysqli $conexion)
    {
        $this->modelo = new Producto($conexion);
    }

    // Procesar la solicitud recibida.
    public function manejarSolicitud(): void
    {
        $accion = $_GET['accion'] ?? 'listar';

        switch ($accion) {
            case 'listar':
                $this->listar();
                break;

            case 'crear':
                $this->crear();
                break;

            case 'editar':
                $this->editar();
                break;

            case 'eliminar':
                $this->eliminar();
                break;

            default:
                http_response_code(404);
                echo "La acción solicitada no existe.";
                break;
        }
    }

    // Mostrar todos los productos.
    private function listar(): void
    {
        $productos = $this->modelo->listar();

        require __DIR__ . '/../views/productos/index.php';
    }

    // Registrar un producto.
    private function crear(): void
    {
        $error = null;
        $artesanos = $this->modelo->obtenerArtesanos();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $artesano_id = filter_input(
                INPUT_POST,
                'artesano_id',
                FILTER_VALIDATE_INT
            );

            $nombre = trim($_POST['nombre'] ?? '');
            $categoria = trim($_POST['categoria'] ?? '');

            $precioTexto = trim($_POST['precio'] ?? '');
            $existenciaTexto = trim($_POST['existencia'] ?? '');
            $minimaTexto = trim($_POST['existencia_minima'] ?? '');

            $precioValido = is_numeric($precioTexto);
            $existenciaValida = filter_var(
                $existenciaTexto,
                FILTER_VALIDATE_INT
            );
            $minimaValida = filter_var(
                $minimaTexto,
                FILTER_VALIDATE_INT
            );

            if (!validarTokenCSRF()) {
                http_response_code(403);
                echo "Solicitud rechazada: token de seguridad inválido.";
                return;
            }

            if (
                !$artesano_id ||
                $nombre === '' ||
                $categoria === '' ||
                !$precioValido ||
                !$existenciaValida &&
                $existenciaValida !== 0 ||
                !$minimaValida &&
                $minimaValida !== 0
            ) {
                $error = 'Completa todos los campos con valores válidos.';
            } elseif (
                (float) $precioTexto <= 0 ||
                (int) $existenciaTexto < 0 ||
                (int) $minimaTexto < 0
            ) {
                $error = 'El precio debe ser mayor que cero y las existencias no pueden ser negativas.';
            } else {
                $this->modelo->crear(
                    (int) $artesano_id,
                    $nombre,
                    $categoria,
                    (float) $precioTexto,
                    (int) $existenciaTexto,
                    (int) $minimaTexto
                );

                header('Location: index.php?accion=listar');
                exit;
            }
        }

        require __DIR__ . '/../views/productos/crear.php';
    }

    // Editar un producto.
    private function editar(): void
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!validarTokenCSRF()) {
                http_response_code(403);
                echo "Solicitud rechazada: token de seguridad inválido.";
                return;
            }

        if (!$id) {
            http_response_code(400);
            echo "El identificador del producto no es válido.";
            return;
        }

        $producto = $this->modelo->obtenerPorId($id);

        if (!$producto) {
            http_response_code(404);
            echo "El producto no existe.";
            return;
        }

        $artesanos = $this->modelo->obtenerArtesanos();
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $artesano_id = filter_input(
                INPUT_POST,
                'artesano_id',
                FILTER_VALIDATE_INT
            );

            $nombre = trim($_POST['nombre'] ?? '');
            $categoria = trim($_POST['categoria'] ?? '');

            $precioTexto = trim($_POST['precio'] ?? '');
            $existenciaTexto = trim($_POST['existencia'] ?? '');
            $minimaTexto = trim($_POST['existencia_minima'] ?? '');

            $precioValido = is_numeric($precioTexto);
            $existenciaValida = filter_var(
                $existenciaTexto,
                FILTER_VALIDATE_INT
            );
            $minimaValida = filter_var(
                $minimaTexto,
                FILTER_VALIDATE_INT
            );

            if (
                !$artesano_id ||
                $nombre === '' ||
                $categoria === '' ||
                !$precioValido ||
                ($existenciaValida === false && $existenciaTexto !== '0') ||
                ($minimaValida === false && $minimaTexto !== '0')
            ) {
                $error = 'Completa todos los campos con valores válidos.';
            } elseif (
                (float) $precioTexto <= 0 ||
                (int) $existenciaTexto < 0 ||
                (int) $minimaTexto < 0
            ) {
                $error = 'El precio debe ser mayor que cero y las existencias no pueden ser negativas.';
            } else {
                $this->modelo->actualizar(
                    $id,
                    (int) $artesano_id,
                    $nombre,
                    $categoria,
                    (float) $precioTexto,
                    (int) $existenciaTexto,
                    (int) $minimaTexto
                );

                header('Location: index.php?accion=listar');
                exit;
            }
        }

        require __DIR__ . '/../views/productos/editar.php';
    }

    // Eliminar un producto únicamente mediante POST.
    private function eliminar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo "Método no permitido.";
            return;
        }

        $id = filter_input(
            INPUT_POST,
            'id',
            FILTER_VALIDATE_INT
        );

        if (!validarTokenCSRF()) {
            http_response_code(403);
            echo "Solicitud rechazada: token de seguridad inválido.";
            return;
        }

        if (!$id) {
            http_response_code(400);
            echo "El identificador del producto no es válido.";
            return;
        }

        $this->modelo->eliminar($id);

        header('Location: index.php?accion=listar');
        exit;
    }
}