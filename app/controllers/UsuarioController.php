<?php

require_once __DIR__ . '/../models/Usuario.php';

class UsuarioController
{
    private Usuario $modelo;

    public function __construct(mysqli $conexion)
    {
        $this->modelo = new Usuario($conexion);
    }

    // Mostrar y procesar el inicio de sesión.
    public function iniciarSesion(): void
    {
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $usuario = trim($_POST['usuario'] ?? '');
            $password = $_POST['password'] ?? '';

            if ($usuario === '' || $password === '') {
                $error = 'Completa todos los campos.';
            } else {

                $datos = $this->modelo->buscarPorUsuario($usuario);

                if (
                    !$datos ||
                    !$datos['estado'] ||
                    !password_verify($password, $datos['password'])
                ) {
                    $error = 'Usuario o contraseña incorrectos.';
                } else {

                    session_regenerate_id(true);

                    $_SESSION['usuario_id'] = $datos['id'];
                    $_SESSION['nombre'] = $datos['nombre'];
                    $_SESSION['rol'] = $datos['rol'];

                    header('Location: index.php?accion=listar');
                    exit;
                }
            }
        }

        require __DIR__ . '/../views/login.php';
    }

    // Cerrar sesión.
    public function cerrarSesion(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $parametros = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $parametros['path'],
                $parametros['domain'],
                $parametros['secure'],
                $parametros['httponly']
            );
        }

        session_destroy();

        header('Location: index.php?accion=login');
        exit;
    }
}
