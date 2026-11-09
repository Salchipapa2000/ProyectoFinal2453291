<?php
function escapar($valor) {
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

$esAdministrador = $_SESSION['rol'] === 'administrador';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel principal | Manos de Chichi</title>
    <link rel="stylesheet" href="css/estilos.css">

    <style>
        .bienvenida {
            text-align: center;
            margin-bottom: 35px;
        }

        .bienvenida h1 {
            margin-bottom: 10px;
        }

        .menu-principal {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .tarjeta-menu {
            background: white;
            border: 1px solid #ddd;
            border-radius: 12px;
            padding: 25px;
            text-align: center;
            text-decoration: none;
            color: inherit;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .tarjeta-menu:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.12);
        }

        .icono-menu {
            font-size: 42px;
            margin-bottom: 15px;
        }

        .tarjeta-menu h2 {
            margin-bottom: 10px;
        }

        .tarjeta-menu p {
            font-size: 14px;
        }

        .sesion {
            text-align: center;
            margin-top: 35px;
        }

        .boton-salir {
            display: inline-block;
            padding: 10px 20px;
            background: #8b3333;
            color: white;
            text-decoration: none;
            border-radius: 6px;
        }

        .boton-salir:hover {
            background: #672222;
        }
    </style>
</head>

<body>

<main class="contenedor">

    <section class="bienvenida">

        <h1>Manos de Chichi</h1>

        <p>Sistema de gestión de productos artesanales</p>

        <h2>
            ¡Bienvenido/a, <?= escapar($_SESSION['nombre']) ?>!
        </h2>

        <p>
            Rol:
            <?= $esAdministrador ? 'Administrador' : 'Vendedor' ?>
        </p>

        <p>Selecciona el módulo al que deseas ingresar.</p>

    </section>

    <section class="menu-principal">

        <a class="tarjeta-menu" href="index.php?accion=listar">

            <div class="icono-menu">📦</div>

            <h2>Productos</h2>

            <p>
                Consultar el inventario de productos artesanales.
                <?= $esAdministrador
                    ? 'Registrar, editar y administrar productos.'
                    : 'Consultar productos y existencias.' ?>
            </p>

        </a>

        <a class="tarjeta-menu" href="index.php?accion=ventas">

            <div class="icono-menu">🛒</div>

            <h2>Ventas</h2>

            <p>
                Registrar ventas y consultar la disponibilidad
                de los productos.
            </p>

        </a>

        <a class="tarjeta-menu" href="index.php?accion=liquidaciones">

            <div class="icono-menu">📊</div>

            <h2>Liquidaciones mensuales</h2>

            <p>
                Consultar los montos vendidos y los pagos
                correspondientes a los artesanos.
            </p>

        </a>

    </section>

    <div class="sesion">

        <a class="boton-salir" href="index.php?accion=logout">
            Cerrar sesión
        </a>

    </div>

</main>

</body>
</html>
