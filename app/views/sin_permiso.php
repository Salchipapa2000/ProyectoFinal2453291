<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso restringido | Manos de Chichi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body class="pantalla-centrada">

    <main class="contenedor tarjeta-aviso">
        <div class="icono-aviso">
        </div>

        <h1> <i class="fa-solid fa-lock"></i> Acceso restringido</h1>

        <p class="mensaje-aviso">
            <?= htmlspecialchars($mensajeAcceso ?? 'No tienes permisos para realizar esta acción.', ENT_QUOTES, 'UTF-8') ?>
        </p>

        <div class="acciones-aviso">
            <a class="boton" href="index.php?accion=inicio">
                <i class="fa-solid fa-house"></i> Ir al inicio
            </a>

            <a class="boton boton-secundario" href="index.php?accion=listar">
                <i class="fa-solid fa-boxes-stacked"></i> Ver productos
            </a>
        </div>
    </main>

</body>
</html>